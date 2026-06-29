<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarsAdminController extends Controller
{
    public function index(): View {
        return view();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View {
        return view();
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse {
        return response()->json([]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse {
        return response()->json([]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View {
        return view();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse {
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse {
        return response()->json([]);
    }
}
