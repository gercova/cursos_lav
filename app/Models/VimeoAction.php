<?php 

namespace App\Models;

use App\Services\VimeoService;
use Illuminate\Support\Facades\Log;


class VimeoAction {
    
    protected $service;
    
    public function __construct()
    {
       $this->service=new VimeoService();
    }

    public  function getUri($vimeoId)
    {

        return '/videos/'.$vimeoId;
    }

    public function getHash(array $videoData)
    {
        $parts = explode('/', parse_url($videoData['link'], PHP_URL_PATH));
        $hash = end($parts);
        return $hash;
    }

    public function getLocalStatus($status)
    {
        $vimeoStatus=[
            'available'=>'ready',
            'transcoding'=>'processing',
            'uploading'=>'uploading',
            'transcoding_error'=>'error',
            'uploading_error'=>'error',
        ];
        return $vimeoStatus[$status]??'pending';
    }
    /*public function create($lesson,$file)
    {
        set_time_limit(0);
        $title=$lesson->title_vimeo;
        $data=$this->upload($file,$title);
        $vim= new Video();
        $vim->lesson_id=$lesson->id;
        $vim->vimeo_id=$data['vimeo_id'];
        $vim->title=$title;
        $vim->hash=$data['hash'];
        $vim->status=$data['status'];
        $vim->save();
        return $vim;
    }
    private function upload($file,$title)
    {
        $uri=$this->service->upload($file->getPathname(),$title);
        $response=$this->service->get($uri);
        if($response['status']==200){
            $videoData = $response['body'];
            return [
                'vimeo_id'=>basename($uri),
                'status'=>$this->getLocalStatus($videoData['status']),
                'hash'=>$this->getHash($videoData)
            ];
        }
        return [
            'vimeo_id'=>basename($uri),
            'status'=>'pending',
            'hash'=>''
        ];
    }
    public function update($id,$lesson,$file)
    {
        set_time_limit(0);
        $obj=Video::find($id);
        if($file && is_null($obj)){
            return $this->create($lesson,$file);
        }
        if(!$file && is_null($obj)){
            return null;
        }
        $title=$lesson->title_vimeo;
        // si no existe file video y  hay cambio de titulo
        $oldTitle = substr($obj->title, 0, -19); // Elimina la fecha (formato: -YYYY-MM-DD-HH-ii-ss)
        if(!$file && $oldTitle!=substr($title, 0, -19)){

            $response=$this->service->update($obj->uri,[
                'name'=>$title
            ]);
            if ($response['status'] === 200) {
                $obj->title=$title;
                $obj->update();
            }else{
                
                Log::info("Error al actualizar: " . $response['body']['error']??'');
            }
            
            return $obj;
        }
        if($file){
            $this->service->delete($obj->uri);
            $data=$this->upload($file,$title);
            $obj->vimeo_id=$data['vimeo_id'];
            $obj->title=$title;
            $obj->hash=$data['hash'];
            $obj->status=$data['status'];
            $obj->save();
            return $obj;
        }
        
        //si existe el archivo eliminamos el anterior del viemo y subimos nuevo
    }*/

    public function delete(Video $vimeo)
    {
        $this->service->delete($vimeo->uri);
        $vimeo->delete();
    }

    public function createDirect($lesson,$vimeoId)
    {
        $uri=$this->getUri($vimeoId);
        $title=$lesson->title_vimeo;
        $this->service->update($uri,[
            'name'=>$title
        ]);
        $data=$this->getData($uri);
        $data['title']=$title;
        $data['vimeo_id']=$vimeoId;
        $lesson->video()->create($data);
        
    }
    
    public function updateDirect($id,$lesson,$vimeoId) {
        $obj=Video::find($id);
        if(!$vimeoId && is_null($obj)){
            return null;
        }
        if($vimeoId && is_null($obj)){
            return $this->createDirect($lesson,$vimeoId);
        }
        $title=$lesson->title_vimeo;
        $oldTitle = substr($obj->title, 0, -19); // Elimina la fecha (formato: -YYYY-MM-DD-HH-ii-ss)

        // si el vime_id es diferente o el titulo es diferente y existe en tabla videos
        if($vimeoId != $obj->vimeo_id || $oldTitle!=substr($title, 0, -19) ){
            $uri=$this->getUri($vimeoId);
            
            $this->service->update($uri,[
                'name'=>$title
            ]);
            $data=$this->getData($uri);
            $data['title']=$title;
            $data['vimeo_id']=$vimeoId;

            $obj->title=$data['title'];
            $obj->vimeo_id=$data['vimeo_id'];
            $obj->status=$data['status'];
            $obj->hash=$data['hash'];
            $obj->update();
        }
    }
    public function getData($uri)
    {
        $response=$this->service->get($uri);
        $data=[];
        $data['status']='pending';
        $data['hash']='';
        if($response['status']==200){
            $videoData = $response['body'];
            $data['status']=$this->getLocalStatus($videoData['status']);
            $data['hash']=$this->getHash($videoData);
        }
        return $data;
    }
}