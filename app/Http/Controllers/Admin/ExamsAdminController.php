<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamsAdminController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View {
        return view('admin.exams.index');
    }

    public function create(Request $request): View {
        return view('admin.exams.create');
    }

    public function edit(Exam $exam, Request $request): View {
        return view('admin.exams.edit', compact('exam'));
    }

    public function show(Exam $exam): JsonResponse {
        return response()->json($exam);
    }

    public function store(Request $request): JsonResponse {
        $exam = Exam::create($request->all());
        return response()->json($exam);
    }

    public function destroy(Exam $exam): JsonResponse {
        $exam->delete();
        return response()->json([
            'status' => true,

        ]);
    }
}
