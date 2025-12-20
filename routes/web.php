<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Admin\CategoriesAdminController;
use App\Http\Controllers\Admin\CoursesAdminController;
use App\Http\Controllers\Admin\CourseSectionAdminController;
use App\Http\Controllers\Admin\DocumentsAdminController;
use App\Http\Controllers\admin\ExamQuestionAdminController;
use App\Http\Controllers\Admin\ExamsAdminController;
use App\Http\Controllers\Admin\LessonsAdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\CartsController;
use App\Http\Controllers\Student\CertificatesController;
use App\Http\Controllers\Student\CoursesController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\StudentExamsController;
use App\Http\Controllers\Student\StudentNotificationController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentProgressController;
use App\Http\Controllers\Student\StudentSettingsController;
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
Route::get('/api/cart/count',   [CartsController::class, 'count'])->name('cart.count');

// Autenticación estudiantes
Route::get('/register',         [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',        [AuthController::class, 'register']);
Route::get('/login',            [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',           [AuthController::class, 'login']);
Route::post('/logout',          [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas para estudiantes
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard',                    [CoursesController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/my-courses',                   [CoursesController::class, 'myCourses'])->name('student.my-courses');

    // Carrito de compras
    Route::get('/cart',                         [CartsController::class, 'index'])->name('cart');
    Route::post('/cart/add/{courseId}',         [CartsController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{courseId}',    [CartsController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout',               [CartsController::class, 'checkout'])->name('cart.checkout');

    // Exámenes
    Route::get('/exam',                         [StudentExamsController::class, 'index'])->name('student.exams');
    //Route::get('/exam/{courseId}',              [ExamsAdminController::class, 'show'])->name('exam.show');
    //Route::post('/exam/{courseId}/start',       [ExamsAdminController::class, 'start'])->name('exam.start');
    //Route::post('/exam/{courseId}/submit',      [ExamsAdminController::class, 'submit'])->name('exam.submit');

    // Certificados
    Route::get('/certificate',                  [CertificatesController::class, 'index'])->name('student.certificates');
    Route::get('/certificate/{certificateId}',  [CertificatesController::class, 'show'])->name('student.certificate.show');
    Route::get('/certificate/{certificateId}/download', [CertificatesController::class, 'download'])->name('certificate.download');

    // Rutas nuevas
    // Dashboard principal
    Route::get('/dashboard',                    [DashboardController::class, 'index'])->name('student.dashboard');

    // Perfil del estudiante
    Route::get('/profile',                      [StudentProfileController::class, 'show'])->name('student.profile');
    Route::put('/profile',                      [StudentProfileController::class, 'update'])->name('student.profile.update');

    // Mis cursos
    Route::get('/courses',                      [CoursesController::class, 'index'])->name('student.courses.index');

    // Progreso
    Route::get('/progress',                     [StudentProgressController::class, 'index'])->name('student.progress');

    // Configuración
    Route::get('/settings',                     [StudentSettingsController::class, 'index'])->name('student.settings');
    Route::put('/settings',                     [StudentSettingsController::class, 'update'])->name('student.settings.update');

    // Notificaciones
    Route::get('/notifications',                [StudentNotificationController::class, 'index'])->name('student.notifications');
});

Route::prefix('admin')->group(function () {
    Route::middleware(['prevent.cache'])->group(function(){
        Route::get('/login',    [AuthAdminController::class, 'showLogin'])->name('admin.login')->middleware('guest');
        Route::post('/login',   [AuthAdminController::class, 'login'])->middleware('guest');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
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

        Route::get('categories/home',               [CategoriesAdminController::class, 'index'])->name('admin.categories.index');
        Route::get('categories/stats',              [CategoriesAdminController::class, 'stats'])->name('admin.categories.stats');
        Route::post('categories/store',             [CategoriesAdminController::class, 'store'])->name('admin.categories.store');
        Route::get('categories/{category}',         [CategoriesAdminController::class, 'show'])->name('admin.categories.show');
        Route::put('categories/{category}',         [CategoriesAdminController::class, 'update'])->name('admin.categories.update');
        Route::delete('categories/{category}',      [CategoriesAdminController::class, 'destroy'])->name('admin.categories.destroy');

        // Acciones especiales
        Route::post('categories/{categoryId}/toggle-status', [CategoriesAdminController::class, 'toggleStatus'])->name('admin.categories.toggle-status');
        Route::post('categories/bulk-action',   [CategoriesAdminController::class, 'bulkAction'])->name('admin.categories.bulk-action');

        // Rutas adicionales para cursos
        Route::get('/courses/home',                             [CoursesAdminController::class, 'index'])->name('admin.courses.index');
        Route::get('/courses/{course}/sections',                [CoursesAdminController::class, 'getSections']);
        Route::post('/courses/{course}/toggle-status',          [CoursesAdminController::class, 'toggleStatus'])->name('admin.courses.toggle-status');
        Route::get('/courses/create',                           [CoursesAdminController::class, 'create'])->name('admin.courses.create');
        Route::post('/courses/store',                           [CoursesAdminController::class, 'store'])->name('admin.courses.store');
        Route::get('/courses/{course}/edit',                    [CoursesAdminController::class, 'edit'])->name('admin.courses.edit');
        Route::put('/courses/update',                           [CoursesAdminController::class, 'update'])->name('admin.courses.update');
        Route::post('/courses/{course}/sections',               [CoursesAdminController::class, 'addSection'])->name('admin.courses.sections.add');
        Route::put('/courses/{course}/sections/{section}',      [CoursesAdminController::class, 'updateSection'])->name('admin.courses.sections.update');
        Route::delete('/courses/{course}/sections/{section}',   [CoursesAdminController::class, 'deleteSection'])->name('admin.courses.sections.delete');

        // Rutas para secciones de cursos
        Route::get('/courses/{course}/sections',                [CourseSectionAdminController::class, 'index'])->name('admin.courses.sections.index');
        Route::get('/courses/{course}/sections/create',         [CourseSectionAdminController::class, 'create'])->name('admin.courses.sections.create');
        Route::post('/courses/{course}/sections',               [CourseSectionAdminController::class, 'store'])->name('admin.courses.sections.store');
        Route::get('/courses/{course}/sections/{section}/edit', [CourseSectionAdminController::class, 'edit'])->name('admin.courses.sections.edit');
        Route::put('/courses/{course}/sections/{section}',      [CourseSectionAdminController::class, 'update'])->name('admin.courses.sections.update');
        Route::delete('/courses/{course}/sections/{section}',   [CourseSectionAdminController::class, 'destroy'])->name('admin.courses.sections.destroy');
        Route::post('/courses/{course}/sections/{section}/toggle-status', [CourseSectionAdminController::class, 'toggleStatus'])->name('admin.courses.sections.toggle-status');
        Route::post('/courses/{course}/sections/reorder',       [CourseSectionAdminController::class, 'reorder'])->name('admin.courses.sections.reorder');

        // Rutas para lecciones
        Route::get('/courses/{course}/sections/{section}',                          [LessonsAdminController ::class, 'index'])->name('admin.courses.sections.lessons.index');
        Route::get('/courses/{course}/sections/{section}/lessons/create',           [LessonsAdminController::class, 'create'])->name('admin.courses.sections.lessons.create');
        Route::post('/courses/{course}/sections/{section}/lessons/store',           [LessonsAdminController::class, 'store'])->name('admin.courses.sections.lessons.store');
        Route::get('/courses/{course}/sections/{section}/lessons/{lesson}/edit',    [LessonsAdminController::class, 'edit'])->name('admin.courses.sections.lessons.edit');
        Route::put('/courses/{course}/sections/{section}/lessons/{lesson}',         [LessonsAdminController::class, 'update'])->name('admin.courses.sections.lessons.update');
        Route::delete('/courses/{course}/sections/{section}/lessons/{lesson}',      [LessonsAdminController::class, 'destroy'])->name('admin.courses.sections.lessons.destroy');
        Route::post('/courses/{course}/sections/{section}/lessons/{lesson}/toggle-status', [LessonsAdminController::class, 'toggleStatus'])->name('admin.courses.sections.lessons.toggle-status');
        Route::post('/courses/{course}/sections/{section}/lessons/reorder',         [LessonsAdminController::class, 'reorder'])->name('admin.courses.sections.lessons.reorder');

        // Rutas para documentos
        Route::get('/documents/home',                           [DocumentsAdminController::class, 'index'])->name('admin.documents.index');
        Route::get('/documents/create',                         [DocumentsAdminController::class, 'index'])->name('admin.documents.create');
        Route::post('/documents/store',                         [DocumentsAdminController::class, 'store'])->name('admin.documents.store');
        Route::post('/documents/{document}/duplicate',          [DocumentsAdminController::class, 'duplicate'])->name('admin.documents.duplicate');
        Route::get('/documents/{document}',                     [DocumentsAdminController::class, 'show'])->name('admin.documents.show');
        Route::put('/documents/{document}',                     [DocumentsAdminController::class, 'update'])->name('admin.documents.update');
        Route::delete('/documents/{document}',                  [DocumentsAdminController::class, 'destroy'])->name('admin.documents.destroy');
        Route::post('/documents/{document}/toggle-status',      [DocumentsAdminController::class, 'toggleStatus'])->name('admin.documents.toggle-status');

        // Rutas adicionales para exámenes
        Route::get('/exams/home',                               [ExamsAdminController::class, 'index'])->name('admin.exams.index');
        Route::get('/exams/create',                             [ExamsAdminController::class, 'create'])->name('admin.exams.create');
        Route::get('/exams/{exam}/edit',                        [ExamsAdminController::class, 'edit'])->name('admin.exams.edit');
        Route::get('/exams/{exam}/show',                        [ExamsAdminController::class, 'show'])->name('admin.exams.show');
        Route::put('/exams/{exam}',                             [ExamsAdminController::class, 'update'])->name('admin.exams.update');
        Route::get('/exams/{exam}/results',                     [ExamsAdminController::class, 'results'])->name('admin.exams.results');
        Route::get('/exams/{exam}/questions',                   [ExamsAdminController::class, 'questions'])->name('admin.exams.questions');
        Route::post('/exams/store',                             [ExamsAdminController::class, 'store'])->name('admin.exams.store');
        Route::post('/exams/{exam}/toggle-status',              [ExamsAdminController::class, 'toggleStatus'])->name('admin.exams.toggle-status');
        Route::delete('/exams/{exam}/questions/{question}',     [ExamsAdminController::class, 'deleteQuestion'])->name('admin.exams.questions.delete');

        Route::post('exams/{exam}/questions',                   [ExamQuestionAdminController::class, 'store'])->name('admin.exams.questions.store');
        Route::get('exams/questions/{question}/edit',           [ExamQuestionAdminController::class, 'edit'])->name('exams.questions.edit');
        Route::put('exams/questions/{question}',                [ExamQuestionAdminController::class, 'update'])->name('exams.questions.update');
        Route::delete('exams/questions/{question}',             [ExamQuestionAdminController::class, 'destroy'])->name('exams.questions.destroy');
        Route::post('exams/questions/{question}/move',          [ExamQuestionAdminController::class, 'move'])->name('exams.questions.move');
    });
});
