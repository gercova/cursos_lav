<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoriesAdminController;
use App\Http\Controllers\Admin\CoursesAdminController;
use App\Http\Controllers\Admin\CourseSectionAdminController;
use App\Http\Controllers\Admin\DocumentsAdminController;
use App\Http\Controllers\Admin\EnrollmentsAdminController;
use App\Http\Controllers\Admin\EnterpriseAdminController;
use App\Http\Controllers\Admin\ExamQuestionAdminController;
use App\Http\Controllers\Admin\ExamsAdminController;
use App\Http\Controllers\Admin\LessonsAdminController;
use App\Http\Controllers\Admin\PackagesAdminController;
use App\Http\Controllers\Admin\PaymentsAdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\VimeoController;
use App\Http\Controllers\Admin\VimeoWebhookController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Business\BusinessImportController;
use App\Http\Controllers\Business\BusinessManagementController;
use App\Http\Controllers\Student\AffiliateController;
use App\Http\Controllers\Student\CartsController;
use App\Http\Controllers\Student\CertificatesController;
use App\Http\Controllers\Student\CoursesController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Student\PaymentController;
use App\Http\Controllers\Student\StudentExamsController;
use App\Http\Controllers\Student\StudentNotificationController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentProgressController;
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
//ruta webhook 
Route::post('api/vimeo/webhook',[VimeoWebhookController::class,'handle'])->name('webhook');
// Rutas públicas
Route::get('/',                         [AppController::class, 'home'])->name('home');
Route::get('/cursos',                   [AppController::class, 'courses'])->name('cursos');
Route::get('/curso/{slug}',             [AppController::class, 'show'])->name('course.show');
Route::get('/cursos/{code}',            [AppController::class, 'coursesPartner'])->name('cursos-promo');
Route::get('/curso-promo/{slug}/{code}', [AppController::class, 'showPartner'])->name('curso-promo');
Route::get('/nosotros',                 [AppController::class, 'aboutus'])->name('nosotros');
Route::get('/contacto',                 [AppController::class, 'contact'])->name('contacto');
Route::post('/contact/send',            [AppController::class, 'sendMessage'])->name('contact.send');
Route::get('/api/cart/count',           [CartsController::class, 'count'])->name('cart.count');
Route::get('/terminos-y-condiciones',   [AppController::class, 'terms'])->name('terminos-y-condiciones');
Route::get('/politicas-de-uso',         [AppController::class, 'policies'])->name('politicas-de-uso');
Route::get('/politicas-de-cookies',     [AppController::class, 'policies'])->name('politicas-de-cookies');

// Autenticación general (Admin / Instructor / Student)
Route::get('/register',                 [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register',                [RegisterController::class, 'register']);
Route::get('/login',                    [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login',                   [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout',                  [LoginController::class, 'logout'])->name('logout');
Route::get('forgot-password',           [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password',          [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Restablecer contraseña
Route::get('reset-password/{token}',    [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password',           [ResetPasswordController::class, 'reset'])->name('password.update');

// Enlace para verificación del certificado
Route::get('/verify/{code}',            [CertificatesController::class, 'verify'])->name('verify.certificate');

// Rutas protegidas para estudiantes
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard',                    [CoursesController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/my-courses',                   [CoursesController::class, 'myCourses'])->name('student.my-courses');
    Route::get('/dashboard-stats',              [DashboardController::class, 'dashboardStats']);
    Route::get('/dashboard-exams',              [DashboardController::class, 'dashboardExams']);
    Route::get('/dashboard-certificates',       [DashboardController::class, 'dashboardCertificates']);
    Route::get('/recent-activity',              [DashboardController::class, 'recentActivity']);

    // Carrito de compras
    Route::get('/cart',                         [CartsController::class, 'index'])->name('cart');
    Route::post('/cart/add/{course}',           [CartsController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{courseId}',    [CartsController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout',               [CartsController::class, 'checkout'])->name('cart.checkout');

    // Pagos
    Route::get('/checkout',                             [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process-culqi',               [PaymentController::class, 'processCulqiPayment']);
    Route::post('/payment/process-pago-efectivo',       [PaymentController::class, 'processPagoEfectivo']);
    // PagoEfectivo CIP
    Route::get('/payment/cip-instructions/{payment}',   [PaymentController::class, 'cipInstructions'])->name('payment.cip-instructions');
    Route::get('/payment/cip-status/{payment}',         [PaymentController::class, 'cipStatus']);

    // Webhook (sin autenticación)
    // Route::post('/payment/webhook',                     [PaymentController::class, 'webhook']);

    // Rutas para código de promoción
    Route::post('/apply-promo-code',                [PaymentController::class, 'applyPromoCode'])->name('payment.apply-promo-code');
    Route::post('/remove-promo-code',               [PaymentController::class, 'removePromoCode'])->name('payment.remove-promo-code');

    // Listar exámenes
    Route::get('/exams/home',                   [StudentExamsController::class, 'index'])->name('student.exams');
    Route::get('/exams/{id}',                   [StudentExamsController::class, 'show'])->name('student.exams.show');
    Route::post('/exams/{id}/start',            [StudentExamsController::class, 'start'])->name('student.exams.start');
    Route::post('/exams/{id}/save',             [StudentExamsController::class, 'saveAnswers'])->name('student.exams.save-answers');
    Route::post('/exams/{id}/submit',           [StudentExamsController::class, 'submit'])->name('student.exams.submit');
    Route::get('/exams/result/{attemptId}',     [StudentExamsController::class, 'result'])->name('student.exams.result');
    Route::get('/exams/view/{attemptId}',       [StudentExamsController::class, 'view'])->name('student.exams.view');

    // Certificados
    Route::get('/certificate',                          [CertificatesController::class, 'index'])->name('student.certificates');
    Route::get('/certificate/{certificateId}',          [CertificatesController::class, 'show'])->name('student.certificates.show');
    Route::get('/certificate/print/{certificateId}',    [CertificatesController::class, 'print'])->name('student.certificates.print');
    Route::get('/certificate/exact/{certificateId}/download', [CertificatesController::class, 'download'])->name('student.certificates.download-exact');
    
    Route::get('/certificate/exact/{certificateId}/view', [CertificatesController::class, 'viewExact'])->name('student.certificates.view-exact');
    Route::get('/{certificateId}/descargar',            [CertificatesController::class, 'download'])->name('student.certificates.download');
    Route::post('/generar/{enrollmentId}',              [CertificatesController::class, 'generateCertificate'])->name('generate');

    // Dashboard principal
    Route::get('/dashboard',                        [DashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/api/student/dashboard-stats',      [DashboardController::class, 'stats'])->name('student.dashboard.stats');
    Route::get('/api/student/dashboard-courses',    [DashboardController::class, 'dashboardCourses'])->name('student.dashboard.courses');
    Route::get('/api/student/recent-activity',      [DashboardController::class, 'recentActivity'])->name('student.recent.activity');
    Route::get('/api/student/upcoming-events',      [DashboardController::class, 'upcomingEvents'])->name('student.upcoming.events');
    Route::get('/api/student/achievements',         [DashboardController::class, 'achievements'])->name('student.achievements');

    // Notificaciones
    Route::get('/notifications',                    [StudentNotificationController::class, 'index'])->name('student.notifications');
    Route::get('/api/student/notifications',        [StudentNotificationController::class, 'apiIndex'])->name('student.notifications.api');
    Route::post('/notifications/{id}/read',         [StudentNotificationController::class, 'markAsRead'])->name('student.notifications.read');
    Route::post('/notifications/read-all',          [StudentNotificationController::class, 'markAllAsRead'])->name('student.notifications.read-all');
    Route::delete('/notifications/{id}',            [StudentNotificationController::class, 'destroy'])->name('student.notifications.delete');
    Route::delete('/notifications',                 [StudentNotificationController::class, 'clearAll'])->name('student.notifications.clear-all');

    // Perfil del estudiante
    Route::get('/profile',                          [StudentProfileController::class, 'show'])->name('student.profile');
    Route::put('/profile',                          [StudentProfileController::class, 'update'])->name('student.profile.update');
    Route::put('/password',                         [StudentProfileController::class, 'updatePassword'])->name('student.profile.update-password');
    Route::post('/photo',                           [StudentProfileController::class, 'updateProfilePhoto'])->name('student.profile.update-photo');
    Route::delete('/photo',                         [StudentProfileController::class, 'deleteProfilePhoto'])->name('student.profile.delete-photo');

    // Estudiante afiliado
    Route::get('/affiliate/dashboard',              [AffiliateController::class, 'dashboard'])->name('student.affiliate.dashboard');
    Route::get('/affiliate/sales',                  [AffiliateController::class, 'sales'])->name('student.affiliate.sales');
    Route::get('/affiliate/reports',                [AffiliateController::class, 'reports'])->name('student.affiliate.reports');
    Route::get('/affiliate/links',                  [AffiliateController::class, 'links'])->name('student.affiliate.links');
    Route::get('/affiliate/api/stats',              [AffiliateController::class, 'getStats'])->name('student.affiliate.api.stats');

    // Mis cursos
    Route::get('/courses',                          [CoursesController::class, 'index'])->name('student.courses.index');
    Route::get('/courses/{course}/learn',           [CoursesController::class, 'learn'])->name('student.course.learn');
    // Vista de lección individual
    Route::get('/courses/{course}/lesson/{lesson}', [LessonController::class, 'show'])->name('lesson.show');
    // Guardar progreso de lección
    Route::post('/lesson/progress/save',            [LessonController::class, 'saveProgress'])->name('lesson.progress.save');
    // Marcar lección como completada
    Route::post('/lesson/complete',                 [LessonController::class, 'complete'])->name('lesson.complete');
    // Progreso
    Route::get('/progress',                         [StudentProgressController::class, 'index'])->name('student.progress');
    // Notificaciones
    Route::get('/notifications',                [StudentNotificationController::class, 'index'])->name('student.notifications');
  
    
    Route::post('/mp/preference', [PaymentController::class, 'createPreference'])->name('mp.preference');
    // Rutas de retorno de Mercado Pago
    Route::get('/pago/exitoso', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/pago/fallido', [PaymentController::class, 'failure'])->name('payment.failure');
    Route::get('/pago/pendiente', [PaymentController::class, 'pending'])->name('payment.pending');

    Route::post('/mp/preference',       [PaymentController::class, 'createPreference'])->name('mp.preference');
    // Rutas de retorno de Mercado Pago
    Route::get('/pago/exitoso',         [PaymentController::class, 'success'])->name('pago.exitoso');
    Route::get('/pago/fallido',         [PaymentController::class, 'failure'])->name('pago.fallido');
    Route::get('/pago/pendiente',       [PaymentController::class, 'pending'])->name('pago.pendiente');

});

Route::prefix('company')->group(function() {
    Route::middleware(['auth', 'business'])->group(function() {
        Route::get('/mis-colaboradores/lista',      [BusinessManagementController::class, 'index'])->name('company.list');
        Route::get('/mi-perfil/{user}',             [BusinessManagementController::class, 'profile'])->name('company.profile');
        Route::post('/mis-colaboradores/crear',     [BusinessManagementController::class, 'storeStaff'])->name('company.create');
        Route::post('/mis-colaboradores/importar',  [BusinessManagementController::class, 'importFile'])->name('company.import.file'); // ← CAMBIADO

        Route::get('/users/import',             [BusinessImportController::class, 'showImportForm'])->name('company.import.form'); // ← CAMBIADO
        Route::post('/users/import',            [BusinessImportController::class, 'import'])->name('company.import.process');
        Route::get('/users/import/template',    [BusinessImportController::class, 'downloadTemplate'])->name('company.import.template');

        Route::get('/enroll/users',         [BusinessManagementController::class, 'enrollUsers'])->name('company.enroll.users');
        Route::post('/enroll/with-code',    [BusinessManagementController::class, 'enrollWithCode'])->name('company.enroll.with-code');
        Route::post('/enroll/bulk',         [BusinessManagementController::class, 'bulkEnroll'])->name('company.enroll.bulk');
        Route::get('/enroll/recent',        [BusinessManagementController::class, 'getRecentEnrollments'])->name('company.enroll.recent');
        Route::get('/users/without-code',   [BusinessManagementController::class, 'getUsersWithoutCode'])->name('company.users.without-code');
        Route::post('/enroll/super-bulk',   [BusinessManagementController::class, 'superBulkEnroll'])->name('company.enroll.super-bulk');
    });
});

Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard',                [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Gestión de Inscripciones
        Route::get('/enrollments/home',                     [EnrollmentsAdminController::class, 'index'])->name('admin.enrollments.index');
        Route::get('/enrollments/{enrollment}',             [EnrollmentsAdminController::class, 'enrollmentShow'])->name('admin.enrollments.show');
        Route::patch('/enrollments/{enrollment}/status',    [EnrollmentsAdminController::class, 'updateEnrollmentStatus'])->name('admin.enrollments.update-status');

        // Gestión de Pagos
        Route::get('/payments/home',                [PaymentsAdminController::class, 'index'])->name('admin.payments.index');
        Route::patch('/payments/{payment}/status',  [PaymentsAdminController::class, 'updatePaymentStatus'])->name('admin.payments.update-status');

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

        Route::get('/enterprise',                   [EnterpriseAdminController::class, 'index'])->name('admin.enterprise.index');
        Route::put('/enterprise',                   [EnterpriseAdminController::class, 'update'])->name('admin.enterprise.update');
        Route::delete('/enterprise/logo',           [EnterpriseAdminController::class, 'deleteLogo'])->name('admin.enterprise.delete-logo');
        Route::delete('/enterprise/favicon',        [EnterpriseAdminController::class, 'deleteFavicon'])->name('admin.enterprise.delete-favicon');

        // Gestión de Usuarios
        Route::get('/users/home',                   [UserAdminController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create',                 [UserAdminController::class, 'create'])->name('admin.users.create');
        Route::get('/users/{user}',                 [UserAdminController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/edit',            [UserAdminController::class, 'edit'])->name('admin.users.edit');
        Route::post('/users/store',                 [UserAdminController::class, 'store'])->name('admin.users.store');
        Route::put('/users/{user}/password',        [UserAdminController::class, 'updatePassword'])->name('admin.users.password');
        Route::delete('/users/{user}',              [UserAdminController::class, 'destroy'])->name('admin.users.destroy');
        Route::patch('/users/{user}/toggle-status', [UserAdminController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::put('/users/create-code/{user}',     [UserAdminController::class, 'createCode'])->name('admin.user.create-code');
        Route::put('/users/policy/{user}',          [UserAdminController::class, 'createLimitUser'])->name('admin.user.policy');
        Route::get('/users/get-policy/{user}',      [UserAdminController::class, 'getLimitUser'])->name('admin.user.get-policy');

        // Rutas para asignar permisos a usuarios
        Route::get('/roles/home',               [RoleController::class, 'index'])->name('admin.roles.index');
        Route::post('/roles/store',             [RoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/users/{user}/permissions', [RoleController::class, 'assignPermissions'])->name('users.permissions.assign');
        Route::put('/users/{user}/permissions', [RoleController::class, 'updatePermissions'])->name('users.permissions.update');
        Route::delete('/roles/{role}',          [RoleController::class, 'destroy'])->name('admin.roles.destroy');

        // Rutas para categorias
        Route::get('categories/home',                           [CategoriesAdminController::class, 'index'])->name('admin.categories.index');
        Route::get('categories/stats',                          [CategoriesAdminController::class, 'stats'])->name('admin.categories.stats');
        Route::post('categories/store',                         [CategoriesAdminController::class, 'store'])->name('admin.categories.store');
        Route::get('categories/{category}',                     [CategoriesAdminController::class, 'show'])->name('admin.categories.show');
        Route::put('categories/{category}',                     [CategoriesAdminController::class, 'update'])->name('admin.categories.update');
        Route::delete('categories/{category}',                  [CategoriesAdminController::class, 'destroy'])->name('admin.categories.destroy');
        Route::post('categories/{categoryId}/toggle-status',    [CategoriesAdminController::class, 'toggleStatus'])->name('admin.categories.toggle-status');
        Route::post('categories/bulk-action',                   [CategoriesAdminController::class, 'bulkAction'])->name('admin.categories.bulk-action');

        // Rutas para cursos
        Route::get('/courses/home',                     [CoursesAdminController::class, 'index'])->name('admin.courses.index');
        Route::get('/courses/{course}/sections',        [CoursesAdminController::class, 'getSections']);
        Route::post('/courses/{course}/toggle-status',  [CoursesAdminController::class, 'toggleStatus'])->name('admin.courses.toggle-status');
        Route::get('/courses/create',                   [CoursesAdminController::class, 'create'])->name('admin.courses.create');
        Route::get('/courses/{course}',                 [CoursesAdminController::class, 'show'])->name('admin.courses.show');
        Route::post('/courses/store',                   [CoursesAdminController::class, 'store'])->name('admin.courses.store');
        Route::get('/courses/{course}/edit',            [CoursesAdminController::class, 'edit'])->name('admin.courses.edit');
        Route::put('/courses/update',                   [CoursesAdminController::class, 'update'])->name('admin.courses.update');
        Route::delete('/courses/{course}',              [CoursesAdminController::class, 'destroy'])->name('admin.courses.destroy');
        // Route::post('/courses/{course}/sections',               [CoursesAdminController::class, 'addSection'])->name('admin.courses.sections.add');
        // Route::put('/courses/{course}/sections/{section}',      [CoursesAdminController::class, 'updateSection'])->name('admin.courses.sections.update');
        // Route::delete('/courses/{course}/sections/{section}',   [CoursesAdminController::class, 'deleteSection'])->name('admin.courses.sections.delete');

        // Rutas para secciones de cursos
        Route::get('/courses/{course}/sections',                [CourseSectionAdminController::class, 'index'])->name('admin.courses.sections.index');
        Route::get('/courses/{course}/sections/create',         [CourseSectionAdminController::class, 'create'])->name('admin.courses.sections.create');
        Route::post('/courses/{course}/sections',               [CourseSectionAdminController::class, 'store'])->name('admin.courses.sections.store');
        Route::get('/courses/{course}/sections/{section}/edit', [CourseSectionAdminController::class, 'edit'])->name('admin.courses.sections.edit');
        Route::put('/courses/{course}/sections/{section}',      [CourseSectionAdminController::class, 'update'])->name('admin.courses.sections.update');
        Route::post('/courses/{course}/sections/{section}/toggle-status', [CourseSectionAdminController::class, 'toggleStatus'])->name('admin.courses.sections.toggle-status');
        Route::post('/courses/{course}/sections/reorder',       [CourseSectionAdminController::class, 'reorder'])->name('admin.courses.sections.reorder');
        Route::delete('/courses/{course}/sections/{section}',   [CourseSectionAdminController::class, 'destroy'])->name('admin.courses.sections.destroy');

        // Rutas para lecciones
        Route::get('/courses/{course}/sections/{section}',                          [LessonsAdminController ::class, 'index'])->name('admin.courses.sections.lessons.index');
        Route::get('/courses/{course}/sections/{section}/lessons/create',           [LessonsAdminController::class, 'create'])->name('admin.courses.sections.lessons.create');
        Route::post('/courses/{course}/sections/{section}/lessons/store',           [LessonsAdminController::class, 'store'])->name('admin.courses.sections.lessons.store');
        Route::get('/courses/{course}/sections/{section}/lessons/{lesson}/edit',    [LessonsAdminController::class, 'edit'])->name('admin.courses.sections.lessons.edit');
        Route::put('/courses/{course}/sections/{section}/lessons/{lesson}',         [LessonsAdminController::class, 'update'])->name('admin.courses.sections.lessons.update');
        Route::delete('/courses/{course}/sections/{section}/lessons/{lesson}',      [LessonsAdminController::class, 'destroy'])->name('admin.courses.sections.lessons.destroy');
        Route::post('/courses/{course}/sections/{section}/lessons/{lesson}/toggle-status', [LessonsAdminController::class, 'toggleStatus'])->name('admin.courses.sections.lessons.toggle-status');
        Route::post('/courses/{course}/sections/{section}/lessons/reorder',         [LessonsAdminController::class, 'reorder'])->name('admin.courses.sections.lessons.reorder');
        
        // rutas vimeo directo
        Route::post('/vimeo/upload-link',       [VimeoController::class,'uploadLink'])->name('vimeo.upload-link');
        Route::delete('/vimeo/{vimeoId}',       [VimeoController::class,'destroy'])->name('vimeo.destroy');
        
        // Rutas para documentos
        Route::get('/documents/home',                       [DocumentsAdminController::class, 'index'])->name('admin.documents.index');
        Route::get('/documents/create',                     [DocumentsAdminController::class, 'index'])->name('admin.documents.create');
        Route::get('/documents/{document}/edit',            [DocumentsAdminController::class, 'edit'])->name('admin.documents.edit');
        Route::post('/documents/store',                     [DocumentsAdminController::class, 'store'])->name('admin.documents.store');
        Route::post('/documents/{document}/duplicate',      [DocumentsAdminController::class, 'duplicate'])->name('admin.documents.duplicate');
        Route::get('/documents/{document}',                 [DocumentsAdminController::class, 'show'])->name('admin.documents.show');
        Route::put('/documents/{document}',                 [DocumentsAdminController::class, 'update'])->name('admin.documents.update');
        Route::delete('/documents/{document}',              [DocumentsAdminController::class, 'destroy'])->name('admin.documents.destroy');
        Route::post('/documents/{document}/toggle-status',  [DocumentsAdminController::class, 'toggleStatus'])->name('admin.documents.toggle-status');

        // Rutas para paquetes
        Route::get('/packeges/home',                        [PackagesAdminController::class, 'index'])->name('admin.packages.index');
        Route::get('/packeges/create',                      [PackagesAdminController::class, 'create'])->name('admin.packages.create');
        Route::get('/packeges/{package}/edit',              [PackagesAdminController::class, 'edit'])->name('admin.packages.edit');
        Route::post('/packeges/store',                      [PackagesAdminController::class, 'store'])->name('admin.packages.store');
        Route::delete('/packeges/{package}',                [PackagesAdminController::class, 'destroy'])->name('admin.packages.destroy');
        Route::post('/packages/{package}/toggle-status',    [PackagesAdminController::class, 'toggleStatus'])->name('admin.packages.toggle-status');

        // Rutas adicionales para exámenes
        Route::get('/exams/home',                               [ExamsAdminController::class, 'index'])->name('admin.exams.index');
        Route::get('/exams/{exam}/edit',                        [ExamsAdminController::class, 'edit'])->name('admin.exams.edit');
        Route::post('/exams/{exam}/duplicate',                  [ExamsAdminController::class, 'duplicate'])->name('admin.exams.duplicate');
        Route::get('/exams/{exam}/show',                        [ExamsAdminController::class, 'show'])->name('admin.exams.show');
        Route::put('/exams/{exam}',                             [ExamsAdminController::class, 'update'])->name('admin.exams.update');
        Route::get('/exams/{id}/details',                       [ExamsAdminController::class, 'attemptDetails'])->name('admin.exams.details');
        Route::get('/exams/{exam}/results/export',              [ExamsAdminController::class, 'exportResults'])->name('admin.exams.results.export');
        Route::get('/exams/{exam}/results',                     [ExamsAdminController::class, 'results'])->name('admin.exams.results');
        Route::get('/exams/{exam}/questions',                   [ExamsAdminController::class, 'questions'])->name('admin.exams.questions');
        Route::post('/exams/store',                             [ExamsAdminController::class, 'store'])->name('admin.exams.store');
        Route::post('/exams/{exam}/toggle-status',              [ExamsAdminController::class, 'toggleStatus'])->name('admin.exams.toggle-status');
        Route::delete('/exams/{exam}',                          [ExamsAdminController::class, 'destroy'])->name('admin.exams.delete');
        Route::delete('/exams/{exam}/questions/{question}',     [ExamsAdminController::class, 'deleteQuestion'])->name('admin.exams.questions.delete');

        Route::post('/exams/{exam}/questions',                  [ExamQuestionAdminController::class, 'store'])->name('admin.exams.questions.store');
        Route::get('/exams/questions/{question}/edit',          [ExamQuestionAdminController::class, 'edit'])->name('exams.questions.edit');
        Route::put('/exams/questions/{question}',               [ExamQuestionAdminController::class, 'update'])->name('exams.questions.update');
        Route::delete('/exams/questions/{question}',            [ExamQuestionAdminController::class, 'destroy'])->name('exams.questions.destroy');
        Route::post('/exams/questions/{question}/move',         [ExamQuestionAdminController::class, 'move'])->name('exams.questions.move');
        Route::post('/exams/{exam}/questions/import',           [ExamQuestionAdminController::class, 'import'])->name('admin.exams.questions.import');
        Route::post('/exams/{exam}/questions/reorder',          [ExamQuestionAdminController::class, 'reorder'])->name('admin.exams.questions.reorder');
        Route::post('/exams/questions/{question}/move',         [ExamQuestionAdminController::class, 'move'])->name('admin.exams.questions.move');

    });
});
