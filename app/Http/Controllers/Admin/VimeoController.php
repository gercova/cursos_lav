<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VimeoService;
use Illuminate\Http\Request;

class VimeoController extends Controller
{
    public function uploadLink(Request $request,VimeoService $vimeoService)
    {

        $response=$vimeoService->getUploadLink($request->input('size'),$request->input('name'));

        return response()->json($response);
    }
    
    public function destroy($vimeoId)
    {

    }
}