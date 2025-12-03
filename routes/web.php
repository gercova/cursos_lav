<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Admin\CategoriesAdminController;
use App\Http\Controllers\Admin\CoursesAdminController;
use App\Http\Controllers\Admin\DocumentsAdminController;
use App\Http\Controllers\Admin\ExamsAdminController;
use App\Http\Controllers\ContactController;
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
    Route::get('/login',    [AuthAdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login',   [AuthAdminController::class, 'login'])->name('admin.login');

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/dashboard',                [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Gestión de Usuarios
        Route::get('/users',                    [AdminController::class, 'usersIndex'])->name('admin.users.index');
        Route::get('/users/create',             [AdminController::class, 'userCreate'])->name('admin.users.create');
        Route::post('/users',                   [AdminController::class, 'userStore'])->name('admin.users.store');
        Route::get('/users/{user}',             [AdminController::class, 'userShow'])->name('admin.users.show');
        Route::get('/users/{user}/edit',        [AdminController::class, 'userEdit'])->name('admin.users.edit');
        Route::put('/users/{user}',             [AdminController::class, 'userUpdate'])->name('admin.users.update');
        Route::delete('/users/{user}',          [AdminController::class, 'userDestroy'])->name('admin.users.destroy');
        Route::patch('/users/{user}/toggle-status',         [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');

        // Gestión de Inscripciones
        Route::get('/enrollments',                          [AdminController::class, 'enrollmentsIndex'])->name('admin.enrollments.index');
        Route::get('/enrollments/{enrollment}',             [AdminController::class, 'enrollmentShow'])->name('admin.enrollments.show');
        Route::patch('/enrollments/{enrollment}/status',    [AdminController::class, 'updateEnrollmentStatus'])->name('admin.enrollments.update-status');

        // Gestión de Pagos
        Route::get('/payments',                     [AdminController::class, 'paymentsIndex'])->name('admin.payments.index');
        Route::patch('/payments/{payment}/status',  [AdminController::class, 'updatePaymentStatus'])->name('admin.payments.update-status');

        // Reportes
        Route::get('/reports',                      [AdminController::class, 'reports'])->name('admin.reports');

        // Configuración
        Route::get('/settings',                     [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/settings',                    [AdminController::class, 'updateSettings'])->name('admin.settings.update');

        // Mantenimiento
        Route::get('/maintenance',                  [AdminController::class, 'maintenance'])->name('admin.maintenance');
        Route::post('/backup',                      [AdminController::class, 'runBackup'])->name('admin.backup.run');
        Route::post('/clear-cache',                 [AdminController::class, 'clearCache'])->name('admin.cache.clear');

        // Log de Actividades
        Route::get('/activity-log',                 [AdminController::class, 'activityLog'])->name('admin.activity-log');

        // Perfil
        Route::get('/profile',                      [AdminController::class, 'profile'])->name('admin.profile');
        Route::put('/profile',                      [AdminController::class, 'updateProfile'])->name('admin.profile.update');

        // Logout
        Route::post('/logout',                      [AuthAdminController::class, 'logout'])->name('admin.logout');

        // CRUDs existentes
        // Route::resource('categories',               CategoriesAdminController::class);
        // Route::resource('courses',                  CoursesAdminController::class);
        // Route::resource('documents',                DocumentsAdminController::class);
        // Route::resource('exams',                    ExamsAdminController::class);

        // Rutas adicionales para categorías

        /*Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/home',                         [CategoriesAdminController::class, 'index'])->name('index');
            Route::post('/store',                       [CategoriesAdminController::class, 'store'])->name('store');
            Route::put('/{category}',                   [CategoriesAdminController::class, 'update'])->name('update');
            Route::delete('/{category}',                [CategoriesAdminController::class, 'destroy'])->name('destroy');
            Route::post('/{category}/toggle-status',    [CategoriesAdminController::class, 'toggleStatus'])->name('toggle-status');
        });*/
        Route::get('/categories/home',                          [CategoriesAdminController::class, 'index'])->name('admin.categories.index');
        Route::get('/categories/create',                        [CategoriesAdminController::class, 'create'])->name('admin.categories.create');
        Route::get('/categories/{category}/edit',               [CategoriesAdminController::class, 'edit'])->name('admin.categories.edit');
        Route::get('/categories/show/{category}',               [CategoriesAdminController::class, 'show'])->name('admin.categories.show');
        Route::patch('/categories/{category}',                  [CategoriesAdminController::class, 'update'])->name('admin.categories.update');
        Route::post('/admin/categories/{category}/toggle-status', [CategoriesAdminController::class, 'toggleStatus'])->name('admin.categories.toggle-status');
        Route::post('/categories/store',                        [CategoriesAdminController::class, 'store'])->name('admin.categories.store');
        Route::delete('/categories/{category}',                 [CategoriesAdminController::class, 'destroy'])->name('admin.categories.destroy');

        // Rutas adicionales para cursos
        Route::get('/courses/home',                             [CoursesAdminController::class, 'index'])->name('admin.courses.index');
        Route::get('/courses/create',                           [CoursesAdminController::class, 'create'])->name('admin.courses.create');
        Route::get('/courses/{course}/edit',                    [CoursesAdminController::class, 'edit'])->name('admin.courses.edit');
        Route::post('/courses/{course}/sections',               [CoursesAdminController::class, 'addSection'])->name('admin.courses.sections.add');
        Route::put('/courses/{course}/sections/{section}',      [CoursesAdminController::class, 'updateSection'])->name('admin.courses.sections.update');
        Route::delete('/courses/{course}/sections/{section}',   [CoursesAdminController::class, 'deleteSection'])->name('admin.courses.sections.delete');

        // Rutas para documentos
        Route::get('/documents/home',                           [DocumentsAdminController::class, 'index'])->name('admin.documents.index');

        // Rutas adicionales para exámenes
        Route::get('/exams/home',                               [ExamsAdminController::class, 'index'])->name('admin.exams.index');
        Route::post('/exams/{exam}/questions',                  [ExamsAdminController::class, 'addQuestion'])->name('admin.exams.questions.add');
        Route::put('/exams/{exam}/questions/{question}',        [ExamsAdminController::class, 'updateQuestion'])->name('admin.exams.questions.update');
        Route::delete('/exams/{exam}/questions/{question}',     [ExamsAdminController::class, 'deleteQuestion'])->name('admin.exams.questions.delete');
    });
});
