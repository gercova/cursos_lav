<?php 

namespace App\Services;

use Vimeo\Vimeo;

class VimeoService{

    protected Vimeo $client;

    public function __construct()
    {
        $this->createClient(config('services.vimeo'));
    }
    public function createClient($config)
    {
        $this->client=new Vimeo(
            $config['client_id']??'',
            $config['client_secret']??'',
            $config['access_token']??''
        );
    }

    public function upload(string $path, string $title): string
    {
        try{
            return $this->client->upload($path, [
                'name' => $title,
                'privacy' => [
                    'view' => 'unlisted',
                    "embed" => "whitelist"//--public
                ]
            ]);
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
        
    }
    public function get(string $uri): array
    {
        return $this->client->request($uri, [], 'GET');
    }

    public function update(string $uri, array $data)
    {
        return $this->client->request($uri, $data, 'PATCH');
    }

    public function delete(string $uri)
    {
        return $this->client->request($uri, [], 'DELETE');
    }

    public function createProject(string $project)
    {
        $response = $this->client->request('/me/projects', [
            'name' => $project
        ], 'POST');

        return $response['body']['uri'];
    }
    public function moveToProject($projectUri,$videoUri)
    {
        return $this->client->request($projectUri . $videoUri,[],'PUT');
    }
}