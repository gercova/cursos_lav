<?php

use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Student\PaymentController;
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

    // Estadísticas del dashboard
    Route::get('/student/dashboard-stats', [DashboardController::class, 'dashboardStats']);
});
Route::post('/mp/webhook', [PaymentController::class, 'webhook'])->name('mp.webhook');
