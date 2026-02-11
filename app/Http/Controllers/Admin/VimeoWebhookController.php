<?php 

namespace App\Http\Controllers\Admin;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VimeoWebhookController {

    public function handle(Request $request) {

        Log::info('requets webhook',$request->all());
        $event      = $request->input('webhook_type');
        $videoUri   = $request->input('data.video_uri'); 

        if (!$videoUri) {
            return response('no se proporciono la url del video', 400);
        }
        $vimeoId    = str_replace('/videos/', '', $videoUri);
        if ($event === 'video-transcode-complete') {
            Video::where('vimeo_id', $vimeoId)->update(['status' => 'ready']);
        }

        if ($event === 'video-upload-failed') {
            Video::where('vimeo_id', $vimeoId)->update(['status' => 'error']);
            Log::info('Webhook de Vimeo recibido error: '.$request->input('error_type'));
        }

        return response('OK', 200);
    }
}