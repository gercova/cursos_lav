<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentsAdminController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View {
        return view('admin.documents.index');
    }

    public function create(): View {
        return view('admin.documents.create');
    }

    public function edit(Document $document): View {
        return view('admin.documents.edit', compact('document'));
    }

    public function show(Document $document): JsonResponse {
        return response()->json($document);
    }

    public function store(Request $request): JsonResponse {
        return response()->json([]);
    }

    public function destroy(Document $document): JsonResponse {
        $document->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}
