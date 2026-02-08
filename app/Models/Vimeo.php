<?php

namespace App\Models;

use App\Services\VimeoService;
use Illuminate\Database\Eloquent\Model;


class Vimeo extends Model {

    protected $table       = 'videos';
    protected $primaryKey   = 'id';

    protected $fillable     = [
        'lesson_id','vimeo_id','title','hash','status'
    ];

    public function upload($lesson,$file)
    {
        set_time_limit(0);
        $status='pending';
        $title=$lesson->title_vimeo;
        $hash='';
        $service=new VimeoService();
        $uri=$service->upload($file->getPathname(),$title);
        $response=$service->get($uri);
        if($response['status']==200){
            $videoData = $response['body'];
            $status=$videoData['status'];
            $hash=$service->getHash($videoData);
        }
        $vim= new static();
        $vim->lesson_id=$lesson->id;
        $vim->vimeo_id=basename($uri);
        $vim->title=$title;
        $vim->hash=$hash;
        $vim->status=$status;
        $vim->save();
        return $vim;
    }

}