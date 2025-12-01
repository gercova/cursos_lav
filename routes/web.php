<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoriesAdminController;
use App\Http\Controllers\Admin\CoursesAdminController;
use App\Http\Controllers\Admin\DocumentsAdminController;
use App\Http\Controllers\Admin\ExamsAdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\CartsController;
use App\Http\Controllers\Student\CertificatesController;
use App\Http\Controllers\Student\CoursesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas públicas
Route::get('/',                 [CoursesController::class, 'index'])->name('home');
Route::get('/cursos',           [CoursesController::class, 'courses'])->name('cursos');
Route::get('/nosotros', function () {
    return view('student.about');
})->name('nosotros');
Route::get('/contacto', function () {
    return view('student.contact');
})->name('contacto');
// Ruta para el formulario de contacto
Route::post('/contact/send',    [ContactController::class, 'sendMessage'])->name('contact.send');

Route::get('/curso/{id}',       [CoursesController::class, 'show'])->name('course.show');

// Autenticación estudiantes
Route::get('/register',         [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',        [AuthController::class, 'register']);
Route::get('/login',            [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',           [AuthController::class, 'login']);
Route::post('/logout',          [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas para estudiantes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard',    [CoursesAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-courses',   [CoursesAdminController::class, 'myCourses'])->name('my-courses');

    // Carrito de compras
    Route::get('/cart',                         [CartsController::class, 'index'])->name('cart');
    Route::post('/cart/add/{courseId}',         [CartsController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{courseId}',    [CartsController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout',               [CartsController::class, 'checkout'])->name('cart.checkout');

    // Exámenes
    Route::get('/exam/{courseId}',              [ExamsAdminController::class, 'show'])->name('exam.show');
    Route::post('/exam/{courseId}/start',       [ExamsAdminController::class, 'start'])->name('exam.start');
    Route::post('/exam/{courseId}/submit',      [ExamsAdminController::class, 'submit'])->name('exam.submit');

    // Certificados
    Route::get('/certificate/{certificateId}',  [CertificatesController::class, 'show'])->name('certificate.show');
    Route::get('/certificate/{certificateId}/download', [CertificatesController::class, 'download'])->name('certificate.download');
});

Route::prefix('admin')->group(function () {
    Route::get('/login',    [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login',   [AdminController::class, 'login']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // CRUD Categorías
        Route::resource('categories',                           CategoriesAdminController::class);

        // CRUD Cursos
        Route::resource('courses',                              CategoriesAdminController::class);
        Route::post('/courses/{course}/sections',               [CategoriesAdminController::class, 'addSection'])->name('courses.sections.add');
        Route::put('/courses/{course}/sections/{section}',      [CategoriesAdminController::class, 'updateSection'])->name('courses.sections.update');
        Route::delete('/courses/{course}/sections/{section}',   [CategoriesAdminController::class, 'deleteSection'])->name('courses.sections.delete');

        // CRUD Documentos
        Route::resource('documents',                            DocumentsAdminController::class);

        // CRUD Exámenes
        Route::resource('exams',                                ExamsAdminController::class);
        Route::post('/exams/{exam}/questions',                  [ExamsAdminController::class, 'addQuestion'])->name('exams.questions.add');
        Route::put('/exams/{exam}/questions/{question}',        [ExamsAdminController::class, 'updateQuestion'])->name('exams.questions.update');
        Route::delete('/exams/{exam}/questions/{question}',     [ExamsAdminController::class, 'deleteQuestion'])->name('exams.questions.delete');
    });
});
