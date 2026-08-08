<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoriesAdminController;
use App\Http\Controllers\Admin\ScheduleAdminController;
use App\Http\Controllers\Admin\CertificatesAdminController;
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
use App\Http\Controllers\Student\AffiliateController;
use App\Http\Controllers\Student\BusinessImportController;
use App\Http\Controllers\Student\CompanyScheduleController;
use App\Http\Controllers\Student\BusinessManagementController;
use App\Http\Controllers\Student\CartsController;
use App\Http\Controllers\Student\CertificatesController;
use App\Http\Controllers\Student\CoursesController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\DashboardPackageController;
use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Student\PackageSelectionController;
use App\Http\Controllers\Student\PaymentController;
use App\Http\Controllers\Student\StudentExamsController;
use App\Http\Controllers\Student\StudentNotificationController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentProgressController;
use App\Http\Controllers\Student\WishlistController;
use Illuminate\Support\Facades\Route;

//ruta webhook 
Route::post('api/vimeo/webhook',            [VimeoWebhookController::class,'handle'])->name('webhook');

// Rutas públicas
Route::get('/',                             [AppController::class, 'home'])->name('home');
Route::get('/cursos/{code?}',               [AppController::class, 'courses'])->name('cursos');
Route::get('/promo-paquetes/{code?}',       [AppController::class, 'packages'])->name('paquetes');
Route::get('/promo-paquete/{slug}/{code?}', [AppController::class, 'showPackage'])->name('paquete.detail');
Route::get('/curso/{slug}/{code?}',         [AppController::class, 'showCourse'])->name('course.show');
Route::get('/nosotros',                     [AppController::class, 'aboutus'])->name('nosotros');
Route::get('/contacto',                     [AppController::class, 'contact'])->name('contacto');
Route::post('/contact/send',                [AppController::class, 'sendMessage'])->name('contact.send')->middleware('throttle:5,1');
Route::get('/api/cart/count',               [CartsController::class, 'count'])->name('cart.count');
Route::get('/terminos-y-condiciones',       [AppController::class, 'terms'])->name('terminos-y-condiciones');
Route::get('/politicas-de-uso',             [AppController::class, 'policies'])->name('politicas-de-uso');
Route::get('/politicas-de-cookies',         [AppController::class, 'cookies'])->name('politicas-de-cookies');

// Autenticación general (Admin / Instructor / Student)
Route::get('/register',                     [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register',                    [RegisterController::class, 'register'])->middleware('throttle:5,1');
Route::get('/login',                        [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login',                       [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout',                      [LoginController::class, 'logout'])->name('logout');
Route::get('forgot-password',               [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password',              [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:5,1');

// Restablecer contraseña
Route::get('reset-password/{token}',        [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password',               [ResetPasswordController::class, 'reset'])->name('password.update')->middleware('throttle:5,1');

// Enlace para verificación del certificado
Route::get('/verify/{code}',                [CertificatesController::class, 'verify'])->name('verify.certificate');

// Rutas protegidas para estudiantes
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard',                    [CoursesController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/mis-cursos',                   [CoursesController::class, 'myCourses'])->name('student.my-courses');
    Route::get('/mis-metas',                    [StudentProgressController::class, 'myGoals'])->name('student.goals');
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
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');

    // Lista de Deseos (Wishlist)
    Route::get('/wishlist',                     [WishlistController::class, 'index'])->name('student.wishlist');
    Route::post('/wishlist/add',                [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/toggle',             [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{courseId}',[WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::delete('/wishlist/clear',            [WishlistController::class, 'clearAll'])->name('wishlist.clear');
    Route::get('/wishlist/count',               [WishlistController::class, 'count'])->name('wishlist.count');
    Route::get('/wishlist/check/{courseId}',    [WishlistController::class, 'check'])->name('wishlist.check');

    // si usuario tiene compro un paquete
    Route::get('/mi-dashboard',                 [DashboardPackageController::class, 'index'])->name('company.dashboard-admin');
    Route::get('/mis-colaboradores/lista',      [BusinessManagementController::class, 'index'])->name('company.list');
    Route::put('/mi-colaborador/{user}/password', [BusinessManagementController::class, 'updatePassword'])->name('company.password');
    Route::get('/mi-perfil/{user}',             [BusinessManagementController::class, 'profile'])->name('company.profile');
    Route::get('/mi-colaborador/crear',         [BusinessManagementController::class, 'createStaff'])->name('company.create.new');
    Route::post('/mis-colaboradores/crear',     [BusinessManagementController::class, 'storeStaff'])->name('company.create');
    Route::post('/mis-colaboradores/importar',  [BusinessManagementController::class, 'importFile'])->name('company.import.file'); // ← CAMBIADO

    Route::get('/mis-colaboradores/importar',   [BusinessImportController::class, 'showImportForm'])->name('company.import.form');
    Route::patch('/mi-colaborador/{user}/toggle-status', [BusinessManagementController::class, 'toggleStatus'])->name('company.toggle-status');
    Route::post('/users/import',                [BusinessImportController::class, 'import'])->name('company.import.process');
    Route::get('/users/import/template',        [BusinessImportController::class, 'downloadTemplate'])->name('company.import.template');
    Route::get('/enroll/users',                 [BusinessManagementController::class, 'enrollUsers'])->name('company.enroll.users');
    Route::post('/enroll/with-code',            [BusinessManagementController::class, 'enrollWithCode'])->name('company.enroll.with-code');
    Route::post('/enroll/bulk',                 [BusinessManagementController::class, 'bulkEnroll'])->name('company.enroll.bulk');
    Route::get('/enroll/recent',                [BusinessManagementController::class, 'getRecentEnrollments'])->name('company.enroll.recent');
    Route::get('/users/without-code',           [BusinessManagementController::class, 'getUsersWithoutCode'])->name('company.users.without-code');
    Route::post('/enroll/super-bulk',           [BusinessManagementController::class, 'superBulkEnroll'])->name('company.enroll.super-bulk');

    // Cronograma de capacitaciones (vista empresa/colaboradores)
    Route::get('/cronograma',                   [CompanyScheduleController::class, 'index'])->name('company.schedule');

    Route::get('/package/{packageId}/select-courses',   [PackageSelectionController::class, 'showSelectionForm'])->name('student.package.select');
    Route::post('/package/{packageId}/save-courses',    [PackageSelectionController::class, 'storeSelection'])->name('student.package.save');
    Route::get('/api/student/package/courses',          [PackageSelectionController::class, 'getCourses']);

    // Listar exámenes
    Route::get('/exams/home',                   [StudentExamsController::class, 'index'])->name('student.exams');
    Route::get('/exams/{id}',                   [StudentExamsController::class, 'show'])->name('student.exams.show');
    Route::post('/exams/{id}/start',            [StudentExamsController::class, 'start'])->name('student.exams.start');
    Route::post('/exams/{id}/save',             [StudentExamsController::class, 'saveAnswers'])->name('student.exams.save-answers');
    Route::post('/exams/{id}/submit',           [StudentExamsController::class, 'submit'])->name('student.exams.submit');
    Route::get('/exams/result/{attemptId}',     [StudentExamsController::class, 'result'])->name('student.exams.result');
    Route::get('/exams/view/{attemptId}',       [StudentExamsController::class, 'view'])->name('student.exams.view');

    // Certificados
    Route::get('/certificate',                                  [CertificatesController::class, 'index'])->name('student.certificates');
    Route::get('/certificate/{certificateId}',                  [CertificatesController::class, 'show'])->name('student.certificates.show');
    Route::get('/certificate/exact/{certificateId}/download',   [CertificatesController::class, 'download'])->name('student.certificates.download-exact');
    Route::get('/certificate/exact/{certificateId}/view',       [CertificatesController::class, 'viewExact'])->name('student.certificates.view-exact');
    Route::post('/generar/{enrollmentId}',                      [CertificatesController::class, 'generateCertificate'])->name('generate');

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
    Route::post('/notifications/{id}/unread',       [StudentNotificationController::class, 'markAsUnread'])->name('student.notifications.unread');
    Route::post('/notifications/read-all',          [StudentNotificationController::class, 'markAllAsRead'])->name('student.notifications.read-all');
    Route::delete('/notifications/{id}',            [StudentNotificationController::class, 'destroy'])->name('student.notifications.delete');
    Route::delete('/notifications',                 [StudentNotificationController::class, 'clearAll'])->name('student.notifications.clear-all');

    // Perfil del estudiante
    Route::get('/profile',                          [StudentProfileController::class, 'show'])->name('student.profile');
    Route::put('/profile',                          [StudentProfileController::class, 'update'])->name('student.profile.update');
    Route::put('/password',                         [StudentProfileController::class, 'updatePassword'])->name('student.profile.update-password');
    Route::post('/photo',                           [StudentProfileController::class, 'updateProfilePhoto'])->name('student.profile.update-photo');
    Route::delete('/photo',                         [StudentProfileController::class, 'deleteProfilePhoto'])->name('student.profile.delete-photo');
    Route::post('/profile/generate-code',           [StudentProfileController::class, 'generatePromoCode'])->name('student.profile.generate-code');

    // Estudiante afiliado
    Route::get('/affiliate/dashboard',              [AffiliateController::class, 'dashboard'])->name('student.affiliate.dashboard');
    Route::get('/affiliate/sales',                  [AffiliateController::class, 'sales'])->name('student.affiliate.sales');
    Route::get('/affiliate/reports',                [AffiliateController::class, 'reports'])->name('student.affiliate.reports');
    Route::get('/affiliate/links',                  [AffiliateController::class, 'links'])->name('student.affiliate.links');
    Route::get('/affiliate/api/stats',              [AffiliateController::class, 'getStats'])->name('student.affiliate.api.stats');
    
    // Ver secciones y lecciones de un curso
    Route::get('/courses/{course}/learn',           [CoursesController::class, 'learn'])->name('student.course.learn');
    // Vista de lección individual
    Route::get('/courses/{course}/lesson/{lesson}', [LessonController::class, 'show'])->name('lesson.show');
    // Guardar progreso de lección
    Route::post('/lesson/progress/save',            [LessonController::class, 'saveProgress'])->name('lesson.progress.save');
    // Marcar lección como completada
    Route::post('/lesson/complete',                 [LessonController::class, 'complete'])->name('lesson.complete');
    
    // Progreso
    Route::get('/progress',                         [StudentProgressController::class, 'index'])->name('student.progress');

    // Route::post('/mp/preference',                   [PaymentController::class, 'createPreference'])->name('mp.preference');
    // Rutas de retorno de Mercado Pago
    // Route::get('/pago/exitoso',                     [PaymentController::class, 'success'])->name('payment.success');
    // Route::get('/pago/fallido',                     [PaymentController::class, 'failure'])->name('payment.failure');
    // Route::get('/pago/pendiente',                   [PaymentController::class, 'pending'])->name('payment.pending');

    Route::post('/mp/preference',                   [PaymentController::class, 'createPreference'])->name('mp.preference');
    // Rutas de retorno de Mercado Pago
    // Route::get('/pago/exitoso',                     [PaymentController::class, 'success'])->name('pago.exitoso');
    Route::get('/pago/fallido',                     [PaymentController::class, 'failure'])->name('pago.fallido');
    Route::get('/pago/pendiente',                   [PaymentController::class, 'pending'])->name('pago.pendiente');
});

Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard',                [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Gestión de Inscripciones
        Route::prefix('enrollments')->name('admin.enrollments.')->group(function(){
            Route::get('/home',                     [EnrollmentsAdminController::class, 'index'])->name('index');
            Route::get('/{enrollment}',             [EnrollmentsAdminController::class, 'enrollmentShow'])->name('show');
            Route::patch('/{enrollment}/status',    [EnrollmentsAdminController::class, 'updateEnrollmentStatus'])->name('update-status');
        });

        // Gestión de Pagos
        Route::prefix('payments')->name('admin.paymens.')->group(function(){
            Route::get('/home',                 [PaymentsAdminController::class, 'index'])->name('index');
            Route::patch('/{payment}/status',   [PaymentsAdminController::class, 'updatePaymentStatus'])->name('update-status');
        });

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

        // Rutas de empresas
        Route::prefix('enterprise')->name('admin.enterprise.')->group(function(){
            Route::get('/index',                    [EnterpriseAdminController::class, 'index'])->name('index');
            Route::put('/update',                   [EnterpriseAdminController::class, 'update'])->name('update');
            Route::delete('/logo',                  [EnterpriseAdminController::class, 'deleteLogo'])->name('delete-logo');
            Route::delete('/favicon',               [EnterpriseAdminController::class, 'deleteFavicon'])->name('delete-favicon');
        });

        // Gestión de Usuarios
        Route::prefix('users')->name('admin.users.')->group(function(){
            Route::get('/home',                     [UserAdminController::class, 'index'])->name('index');
            Route::get('/create',                   [UserAdminController::class, 'create'])->name('create');
            Route::get('/{user}',                   [UserAdminController::class, 'show'])->name('show');
            Route::get('/{user}/edit',              [UserAdminController::class, 'edit'])->name('edit');
            Route::post('/store',                   [UserAdminController::class, 'store'])->name('store');
            Route::put('/{user}/password',          [UserAdminController::class, 'updatePassword'])->name('password');
            Route::put('/{user}/expiration',        [UserAdminController::class, 'updateExpiration'])->name('expiration');
            Route::delete('/{user}',                [UserAdminController::class, 'destroy'])->name('destroy');
            Route::patch('/{user}/toggle-status',   [UserAdminController::class, 'toggleStatus'])->name('toggle-status');
            Route::put('/create-code/{user}',       [UserAdminController::class, 'createCode'])->name('create-code');
            Route::put('/policy/{user}',            [UserAdminController::class, 'createLimitUser'])->name('policy');
            Route::get('/get-policy/{user}',        [UserAdminController::class, 'getLimitUser'])->name('get-policy');
            Route::get('/{user}/sales',             [UserAdminController::class, 'getSales'])->name('sales');
            Route::get('/{user}/activity',          [UserAdminController::class, 'getActivities'])->name('activity');
            Route::get('/courses/search',           [UserAdminController::class, 'searchCourses'])->name('courses.search');
            Route::post('/{user}/enroll',           [UserAdminController::class, 'enrollCourse'])->name('enroll');
        });

        // Rutas para asignar permisos a usuarios
        Route::prefix('roles')->name('admin.roles.')->group(function(){
            Route::get('/home',               [RoleController::class, 'index'])->name('index');
            Route::post('/store',             [RoleController::class, 'store'])->name('store');
            Route::delete('/{role}',          [RoleController::class, 'destroy'])->name('destroy');
        });

        Route::get('/users/{user}/permissions', [RoleController::class, 'assignPermissions'])->name('users.permissions.assign');
        Route::put('/users/{user}/permissions', [RoleController::class, 'updatePermissions'])->name('users.permissions.update');

        // Rutas para categorias
        Route::prefix('categories')->name('admin.categories.')->group(function(){
            Route::get('/home',                          [CategoriesAdminController::class, 'index'])->name('index');
            Route::get('/stats',                         [CategoriesAdminController::class, 'stats'])->name('stats');
            Route::post('/store',                        [CategoriesAdminController::class, 'store'])->name('store');
            Route::get('/{category}',                    [CategoriesAdminController::class, 'show'])->name('show');
            Route::put('/{category}',                    [CategoriesAdminController::class, 'update'])->name('update');
            Route::delete('/{category}',                 [CategoriesAdminController::class, 'destroy'])->name('destroy');
            Route::post('/{categoryId}/toggle-status',   [CategoriesAdminController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/bulk-action',                  [CategoriesAdminController::class, 'bulkAction'])->name('bulk-action');
        });

        // Rutas para cursos
        Route::prefix('courses')->name('admin.courses.')->group(function(){
            Route::get('/home',                     [CoursesAdminController::class, 'index'])->name('index');
            Route::get('/{course}/sections',        [CoursesAdminController::class, 'getSections']);
            Route::get('/create',                   [CoursesAdminController::class, 'create'])->name('create');
            Route::get('/{course}',                 [CoursesAdminController::class, 'show'])->name('show');
            Route::post('/store',                   [CoursesAdminController::class, 'store'])->name('store');
            Route::get('/{course}/edit',            [CoursesAdminController::class, 'edit'])->name('edit');
            Route::get('/{course}/students',        [CoursesAdminController::class, 'students'])->name('students');
            Route::post('/{course}/update-prices',  [CoursesAdminController::class, 'updatePrices'])->name('update-prices');
            Route::post('/{course}/toggle-status',  [CoursesAdminController::class, 'toggleStatus'])->name('toggle-status');
            Route::put('/update',                   [CoursesAdminController::class, 'update'])->name('update');
            Route::delete('/{course}',              [CoursesAdminController::class, 'destroy'])->name('destroy');
        });
        
        // PRIMER GRUPO: Rutas de secciones SIN parámetro {section} (las más específicas primero)
        Route::get('/courses/{course}/sections/create',     [CourseSectionAdminController::class, 'create'])->name('admin.courses.sections.create');
        Route::post('/courses/{course}/sections/reorder',   [CourseSectionAdminController::class, 'reorder'])->name('admin.courses.sections.reorder');
        Route::post('/courses/{course}/sections',           [CourseSectionAdminController::class, 'store'])->name('admin.courses.sections.store');
        Route::get('/courses/{course}/sections',            [CourseSectionAdminController::class, 'index'])->name('admin.courses.sections.index');

        // SEGUNDO GRUPO: Rutas de secciones CON parámetro {section} (las más específicas primero)
        Route::get('/courses/{course}/sections/{section}/edit',             [CourseSectionAdminController::class, 'edit'])->name('admin.courses.sections.edit');
        Route::post('/courses/{course}/sections/{section}/toggle-status',   [CourseSectionAdminController::class, 'toggleStatus'])->name('admin.courses.sections.toggle-status');
        Route::put('/courses/{course}/sections/{section}',                  [CourseSectionAdminController::class, 'update'])->name('admin.courses.sections.update');
        Route::delete('/courses/{course}/sections/{section}',               [CourseSectionAdminController::class, 'destroy'])->name('admin.courses.sections.destroy');

        // TERCER GRUPO: Rutas de lecciones (las más específicas primero)
        Route::get('/courses/{course}/sections/{section}/lessons/create',                   [LessonsAdminController::class, 'create'])->name('admin.courses.sections.lessons.create');
        Route::post('/courses/{course}/sections/{section}/lessons/reorder',                 [LessonsAdminController::class, 'reorder'])->name('admin.courses.sections.lessons.reorder');
        Route::post('/courses/{course}/sections/{section}/lessons/store',                   [LessonsAdminController::class, 'store'])->name('admin.courses.sections.lessons.store');
        Route::get('/courses/{course}/sections/{section}/lessons/{lesson}/edit',            [LessonsAdminController::class, 'edit'])->name('admin.courses.sections.lessons.edit');
        Route::put('/courses/{course}/sections/{section}/lessons/{lesson}',                 [LessonsAdminController::class, 'update'])->name('admin.courses.sections.lessons.update');
        Route::delete('/courses/{course}/sections/{section}/lessons/{lesson}',              [LessonsAdminController::class, 'destroy'])->name('admin.courses.sections.lessons.destroy');
        Route::post('/courses/{course}/sections/{section}/lessons/{lesson}/toggle-status',  [LessonsAdminController::class, 'toggleStatus'])->name('admin.courses.sections.lessons.toggle-status');
        Route::get('/courses/{course}/sections/{section}',                                  [LessonsAdminController::class, 'index'])->name('admin.courses.sections.lessons.index');

        // rutas vimeo directo
        Route::post('/vimeo/upload-link',   [VimeoController::class,'uploadLink'])->name('vimeo.upload-link');
        Route::delete('/vimeo/{vimeoId}',   [VimeoController::class,'destroy'])->name('vimeo.destroy');
        
        // Rutas para documentos
        Route::prefix('documents')->name('admin.documents.')->group(function(){
            Route::get('/home',                       [DocumentsAdminController::class, 'index'])->name('index');
            Route::get('/{course}/create',            [DocumentsAdminController::class, 'create'])->name('create');
            Route::get('/{course}/view',              [DocumentsAdminController::class, 'view'])->name('view');
            Route::get('/{document}/edit',            [DocumentsAdminController::class, 'edit'])->name('edit');
            Route::post('/store',                     [DocumentsAdminController::class, 'store'])->name('store');
            Route::post('/{document}/duplicate',      [DocumentsAdminController::class, 'duplicate'])->name('duplicate');
            Route::get('/{document}',                 [DocumentsAdminController::class, 'show'])->name('show');
            Route::put('/{document}',                 [DocumentsAdminController::class, 'update'])->name('update');
            Route::delete('/{document}',              [DocumentsAdminController::class, 'destroy'])->name('destroy');
            Route::post('/{document}/toggle-status',  [DocumentsAdminController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Rutas para paquetes
        Route::prefix('packages')->name('admin.packages.')->group(function(){
            Route::get('/home',                     [PackagesAdminController::class, 'index'])->name('index');
            Route::get('/create',                   [PackagesAdminController::class, 'create'])->name('create');
            Route::get('/{package}/edit',           [PackagesAdminController::class, 'edit'])->name('edit');
            Route::post('/store',                   [PackagesAdminController::class, 'store'])->name('store');
            Route::put('/{package}/update',         [PackagesAdminController::class, 'update'])->name('update');
            Route::delete('/{package}',             [PackagesAdminController::class, 'destroy'])->name('destroy');
            Route::post('/{package}/toggle-status', [PackagesAdminController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Cronograma de Capacitaciones (Empresas)
        Route::prefix('schedules')->name('admin.schedules.')->group(function () {
            Route::get('/home',             [ScheduleAdminController::class, 'index'])->name('index');
            Route::post('/',                [ScheduleAdminController::class, 'store'])->name('store');
            Route::put('/{schedule}',       [ScheduleAdminController::class, 'update'])->name('update');
            Route::delete('/{schedule}',    [ScheduleAdminController::class, 'destroy'])->name('destroy');
            Route::get('/api',              [ScheduleAdminController::class, 'apiIndex'])->name('api');
            Route::post('/copy-year',       [ScheduleAdminController::class, 'copyYear'])->name('copy-year');
        });

        // Rutas adicionales para exámenes
        Route::prefix('exams')->name('admin.exams.')->group(function(){
            Route::get('/home',                     [ExamsAdminController::class, 'index'])->name('index');
            Route::get('/{course}/create',          [ExamsAdminController::class, 'create'])->name('create');
            Route::get('/{course}/view',            [ExamsAdminController::class, 'view'])->name('view');
            Route::get('/{exam}/edit',              [ExamsAdminController::class, 'edit'])->name('edit');
            Route::post('/{exam}/duplicate',        [ExamsAdminController::class, 'duplicate'])->name('duplicate');
            Route::get('/{exam}/show',              [ExamsAdminController::class, 'show'])->name('show');
            Route::put('/{exam}',                   [ExamsAdminController::class, 'update'])->name('update');
            Route::get('/{id}/details',             [ExamsAdminController::class, 'attemptDetails'])->name('details');
            Route::get('/{exam}/results/export',    [ExamsAdminController::class, 'exportResults'])->name('results.export');
            Route::get('/{exam}/results',           [ExamsAdminController::class, 'results'])->name('results');
            Route::get('/{exam}/questions',         [ExamsAdminController::class, 'questions'])->name('questions');
            Route::post('/store',                   [ExamsAdminController::class, 'store'])->name('store');
            Route::post('/{exam}/toggle-status',    [ExamsAdminController::class, 'toggleStatus'])->name('toggle-status');
            Route::delete('/{exam}',                [ExamsAdminController::class, 'destroy'])->name('destroy');
        });

        // Rutas para las preguntas de los exámanes
        Route::prefix('exams')->name('admin.exams.questions.')->group(function (){
            Route::post('/{exam}/questions',          [ExamQuestionAdminController::class, 'store'])->name('store');
            Route::get('/questions/{question}/edit',  [ExamQuestionAdminController::class, 'edit'])->name('edit');
            Route::put('/questions/{question}',       [ExamQuestionAdminController::class, 'update'])->name('update');
            Route::delete('/questions/{question}',    [ExamQuestionAdminController::class, 'destroy'])->name('destroy');
            Route::post('/questions/{question}/move', [ExamQuestionAdminController::class, 'move'])->name('move');
            Route::post('/{exam}/questions/import',   [ExamQuestionAdminController::class, 'import'])->name('import');
            Route::post('/{exam}/questions/reorder',  [ExamQuestionAdminController::class, 'reorder'])->name('reorder');
        });
        
        // Ruta para ver los certificados
        Route::get('/certificates/{certificate}/view',  [CertificatesAdminController::class, 'show'])->name('admin.certificates.show');
    });
});