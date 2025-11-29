<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CoursesAdminController;
use App\Http\Controllers\Admin\CategoriesAdminController;
use App\Http\Controllers\Admin\DocumentsAdminController;
use App\Http\Controllers\Admin\ExamsAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/login', [AdminController::class, 'login']);
Route::get('/dashboard',                                [AdminController::class, 'dashboard'])->name('admin.dashboard');
// Rutas protegidas
Route::middleware(['auth', 'admin'])->group(function () {

    //Route::get('/dashboard',                                [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout',                                  [AdminController::class, 'logout'])->name('admin.logout');

    // Tus otras rutas admin aquí
    Route::resource('courses',                              CoursesAdminController::class);
    Route::resource('categories',                           CategoriesAdminController::class);

    Route::get('/dashboard',                                [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // CRUD Categorías
    Route::resource('categories',                           CategoriesAdminController::class);

    // CRUD Cursos
    Route::resource('courses',                              CoursesAdminController::class);
    Route::post('/courses/{course}/sections',               [CoursesAdminController::class, 'addSection'])->name('courses.sections.add');
    Route::put('/courses/{course}/sections/{section}',      [CoursesAdminController::class, 'updateSection'])->name('courses.sections.update');
    Route::delete('/courses/{course}/sections/{section}',   [CoursesAdminController::class, 'deleteSection'])->name('courses.sections.delete');

    // CRUD Documentos
    Route::resource('documents', DocumentsAdminController::class);

    // CRUD Exámenes
    Route::resource('exams',                                ExamsAdminController::class);
    Route::post('/exams/{exam}/questions',                  [ExamsAdminController::class, 'addQuestion'])->name('exams.questions.add');
    Route::put('/exams/{exam}/questions/{question}',        [ExamsAdminController::class, 'updateQuestion'])->name('exams.questions.update');
    Route::delete('/exams/{exam}/questions/{question}',     [ExamsAdminController::class, 'deleteQuestion'])->name('exams.questions.delete');
});
