<?php

use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Student\StudentExamsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:sanctum', 'student'])->group(function () {

    // Datos de exámenes para el dashboard
    Route::get('/student/exams', [StudentExamsController::class, 'getExamDataApi']);

    // Notificaciones (ya existe, mantener)
    Route::get('/student/notifications', function () {
        return response()->json([
            'notifications' => [],
            'unreadCount' => 0
        ]);
    });

    // // Carrito (ya existe, mantener)
    // Route::get('/cart/count', function () {
    //     return response()->json(['count' => 0]);
    // });

    // Estadísticas del dashboard
    Route::get('/student/dashboard-stats', function () {
        return response()->json([
            'monthlyProgress' => rand(30, 80)
        ]);
    });

    // Cursos en progreso
    Route::get('/student/progress-courses', function () {
        return response()->json([]);
    });

    // Obtener datos de una lección
    Route::get('/lesson/{lesson}', [LessonController::class, 'show']);

    // Obtener lección anterior/siguiente
    Route::get('/lesson/{lesson}/previous', [LessonController::class, 'previous']);
    Route::get('/lesson/{lesson}/next', [LessonController::class, 'next']);
});
