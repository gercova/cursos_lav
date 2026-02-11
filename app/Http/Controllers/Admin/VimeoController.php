<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\VimeoService;
use Illuminate\Http\Request;

class VimeoController extends Controller
{
    public function uploadLink(Request $request,VimeoService $vimeoService)
    {

        $response=$vimeoService->getUploadLink($request->input('size'),$request->input('name'));

        return response()->json($response);
    }
    
    public function destroy($vimeoId,VimeoService $vimeoService)
    {
        set_time_limit(0);
        $response=$vimeoService->delete("/videos/{$vimeoId}");

        if ($response['status'] === 204) {
            Video::where('vimeo_id',$vimeoId)->update(['vimeo_id'=>null,'hash'=>'']);
            return response()->json(['message' => 'Video eliminado de Vimeo']);
        }

        return response()->json(['error' => 'No se pudo eliminar'], 500);
    }
}