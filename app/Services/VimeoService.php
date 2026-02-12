<?php 

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Vimeo\Vimeo;

class VimeoService{

    protected Vimeo $client;

    public function __construct() {
        $this->createClient(config('services.vimeo'));
    }

    public function createClient($config) {
        $this->client=new Vimeo(
            $config['client_id']??'',
            $config['client_secret']??'',
            $config['access_token']??''
        );
    }

    public function upload(string $path, string $title): string {
        try{
            return $this->client->upload($path, [
                'name'      => $title,
                'privacy'   => [
                    'view'  => 'unlisted',
                    "embed" => "whitelist"//--public
                ]
            ]);
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
        
    }

    public function get(string $uri): array {
        return $this->client->request($uri, [], 'GET');
    }

    public function update(string $uri, array $data) {
        return $this->client->request($uri, $data, 'PATCH');
    }

    public function delete(string $uri) {
        return $this->client->request($uri, [], 'DELETE');
    }

    public function getUploadLink($fileSize, $name=null) {
        try{
            $response = $this->client->request('/me/videos', [
                'upload' => [
                    'approach'  => 'tus',
                    'size'      => $fileSize
                ],
                //'name' => $request->name,
            ], 'POST');
            if($response['status']==200){
                return [
                    'upload_link'   => $response['body']['upload']['upload_link'],
                    'vimeo_uri'     => $response['body']['uri']
                ];
            }
            return [
                'upload_link'   => null,
                'vimeo_uri'     => null
            ];
        }catch(Exception $e){
            Log::error('Error al crear lección: ' . $e->getMessage().' archivo '.$e->getFile().'-'. $e->getLine());
            return [
                'upload_link'   => null,
                'vimeo_uri'     => null
            ];
        }
        
    }

    public function createProject(string $project) {
        $response   = $this->client->request('/me/projects', [
            'name'  => $project
        ], 'POST');

        return $response['body']['uri'];
    }
    
    public function moveToProject($projectUri,$videoUri) {
        return $this->client->request($projectUri . $videoUri,[],'PUT');
    }
}