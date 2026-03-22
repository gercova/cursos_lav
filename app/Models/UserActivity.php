<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model {
    use HasFactory;

    protected $table      = 'user_activities';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'type',
        'action',
        'description',
        'data',
        'ip_address',
        'user_agent',
        'course_id',
        'lesson_id',
        'exam_id',
        'model_type',
        'model_id',
    ];

    protected $casts = [
        'data'       => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─────────────────────────────────────────────
    //  TIPOS DE ACTIVIDAD — sidebar + módulos core
    // ─────────────────────────────────────────────

    // Sesión
    const TYPE_LOGIN            = 'login';
    const TYPE_LOGOUT           = 'logout';
    const TYPE_PASSWORD_CHANGED = 'password_changed';

    // Navegación general
    const TYPE_DASHBOARD_ACCESSED   = 'dashboard_accessed';
    const TYPE_PROFILE_UPDATED      = 'profile_updated';
    const TYPE_PROFILE_ACCESSED     = 'profile_accessed';

    // Módulo: Mis Cursos
    const TYPE_COURSE_ACCESSED      = 'course_accessed';
    const TYPE_COURSES_LIST_VIEWED  = 'courses_list_viewed';
    const TYPE_COURSE_ENROLLED      = 'course_enrolled';
    const TYPE_LESSON_COMPLETED     = 'lesson_completed';
    const TYPE_LESSON_ACCESSED      = 'lesson_accessed';
    const TYPE_COURSE_COMPLETED     = 'course_completed';
    const TYPE_COURSE_SEARCHED      = 'course_searched';

    // Módulo: Exámenes
    const TYPE_EXAMS_LIST_VIEWED  = 'exams_list_viewed';
    const TYPE_EXAM_STARTED       = 'exam_started';
    const TYPE_EXAM_COMPLETED     = 'exam_completed';
    const TYPE_EXAM_FAILED        = 'exam_failed';
    const TYPE_EXAM_RETRIED       = 'exam_retried';

    // Módulo: Certificados
    const TYPE_CERTIFICATES_VIEWED   = 'certificates_viewed';
    const TYPE_CERTIFICATE_EARNED    = 'certificate_earned';
    const TYPE_CERTIFICATE_DOWNLOAD  = 'certificate_download';
    const TYPE_CERTIFICATE_SHARED    = 'certificate_shared';

    // Módulo: Mi Progreso
    const TYPE_PROGRESS_VIEWED = 'progress_viewed';

    // Módulo: Carrito & Compras
    const TYPE_CART_VIEWED       = 'cart_viewed';
    const TYPE_CART_ADDED        = 'cart_added';
    const TYPE_CART_REMOVED      = 'cart_removed';
    const TYPE_CART_CLEARED      = 'cart_cleared';
    const TYPE_PAYMENT_COMPLETED = 'payment_completed';
    const TYPE_PAYMENT_FAILED    = 'payment_failed';

    // Módulo: Wishlist
    const TYPE_WISHLIST_ADDED   = 'wishlist_added';
    const TYPE_WISHLIST_REMOVED = 'wishlist_removed';
    const TYPE_WISHLIST_VIEWED  = 'wishlist_viewed';

    // Módulo: Mis Ventas (Afiliado)
    const TYPE_AFFILIATE_DASHBOARD_VIEWED = 'affiliate_dashboard_viewed';
    const TYPE_AFFILIATE_LINK_COPIED      = 'affiliate_link_copied';
    const TYPE_AFFILIATE_SALE_GENERATED   = 'affiliate_sale_generated';

    // Módulo: Panel Empresa
    const TYPE_COMPANY_PANEL_ACCESSED    = 'company_panel_accessed';
    const TYPE_COMPANY_USERS_MANAGED     = 'company_users_managed';
    const TYPE_COMPANY_USERS_ENROLLED    = 'company_users_enrolled';
    const TYPE_COMPANY_USER_ADDED        = 'company_user_added';
    const TYPE_COMPANY_USER_REMOVED      = 'company_user_removed';
    const TYPE_COMPANY_REPORT_VIEWED     = 'company_report_viewed';

    // ─────────────────────────────────────────────
    //  RELACIONES
    // ─────────────────────────────────────────────

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo {
        return $this->belongsTo(Lesson::class);
    }

    public function exam(): BelongsTo {
        return $this->belongsTo(Exam::class);
    }

    public function relatedModel() {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    // ─────────────────────────────────────────────
    //  DETECCIÓN DE DISPOSITIVO Y NAVEGADOR
    //  Cubre todos los navegadores y SO modernos
    // ─────────────────────────────────────────────

    private static function detectDevice(string $ua): string {
        $ua = strtolower($ua);

        // Orden importante: tablet antes de mobile para evitar falsos positivos
        if (preg_match('/(ipad|tablet|kindle|silk|playbook|surface|nexus\s?(?:7|9|10))/i', $ua)) {
            return 'Tablet';
        }

        if (preg_match('/(android(?!.*tablet)|iphone|ipod|windows phone|mobile|blackberry|bb\d+|meego|symbian|webos|palm|j2me|fennec|minimo|netfront|opera\s?m(?:obi|ini))/i', $ua)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    private static function detectOS(string $ua): string {
        if (preg_match('/windows phone/i', $ua))  return 'Windows Phone';
        if (preg_match('/windows nt 10/i', $ua))  return 'Windows 10/11';
        if (preg_match('/windows nt 6\.3/i', $ua)) return 'Windows 8.1';
        if (preg_match('/windows nt 6\.2/i', $ua)) return 'Windows 8';
        if (preg_match('/windows nt 6\.1/i', $ua)) return 'Windows 7';
        if (preg_match('/windows/i', $ua))         return 'Windows';
        if (preg_match('/iphone os ([\d_]+)/i', $ua, $m)) return 'iOS ' . str_replace('_', '.', $m[1]);
        if (preg_match('/ipad.*os ([\d_]+)/i', $ua, $m))  return 'iPadOS ' . str_replace('_', '.', $m[1]);
        if (preg_match('/android ([\d.]+)/i', $ua, $m))   return 'Android ' . $m[1];
        if (preg_match('/mac os x ([\d_]+)/i', $ua, $m))  return 'macOS ' . str_replace('_', '.', $m[1]);
        if (preg_match('/linux/i', $ua))           return 'Linux';
        if (preg_match('/cros/i', $ua))            return 'ChromeOS';
        return 'Unknown OS';
    }

    private static function detectBrowser(string $ua): string {
        // El orden es crítico: navegadores derivados de Chrome/WebKit deben
        // comprobarse ANTES que Chrome o Safari para evitar detecciones erróneas.

        // Samsung Internet
        if (preg_match('/samsungbrowser\/([\d.]+)/i', $ua, $m))
            return 'Samsung Browser ' . $m[1];

        // UC Browser
        if (preg_match('/ucbrowser\/([\d.]+)/i', $ua, $m))
            return 'UC Browser ' . $m[1];

        // Yandex Browser
        if (preg_match('/yabrowser\/([\d.]+)/i', $ua, $m))
            return 'Yandex Browser ' . $m[1];

        // Vivaldi
        if (preg_match('/vivaldi\/([\d.]+)/i', $ua, $m))
            return 'Vivaldi ' . $m[1];

        // DuckDuckGo
        if (preg_match('/duckduckgo\/([\d.]+)/i', $ua, $m))
            return 'DuckDuckGo ' . $m[1];

        // Brave (no expone token propio; se detecta por la ausencia de tokens
        // de otros browsers + JS-side navigator.brave, pero aquí usamos heurística)
        if (preg_match('/brave\/([\d.]+)/i', $ua, $m))
            return 'Brave ' . $m[1];

        // Opera (nuevo, basado en Chromium — OPR token)
        if (preg_match('/opr\/([\d.]+)/i', $ua, $m))
            return 'Opera ' . $m[1];

        // Opera Mini / Mobile
        if (preg_match('/opera\s?mini\/([\d.]+)/i', $ua, $m))
            return 'Opera Mini ' . $m[1];

        if (preg_match('/opera\/([\d.]+)/i', $ua, $m))
            return 'Opera ' . $m[1];

        // Microsoft Edge (Chromium — Edg token)
        if (preg_match('/edg\/([\d.]+)/i', $ua, $m))
            return 'Microsoft Edge ' . $m[1];

        // Microsoft Edge (Legacy — EdgeHTML)
        if (preg_match('/edge\/([\d.]+)/i', $ua, $m))
            return 'Microsoft Edge (Legacy) ' . $m[1];

        // Internet Explorer 11
        if (preg_match('/trident\/7\.0.*rv:([\d.]+)/i', $ua, $m))
            return 'Internet Explorer 11';

        // Internet Explorer 6–10
        if (preg_match('/msie ([\d.]+)/i', $ua, $m))
            return 'Internet Explorer ' . $m[1];

        // Firefox
        if (preg_match('/firefox\/([\d.]+)/i', $ua, $m))
            return 'Firefox ' . $m[1];

        // Chrome (debe ir después de todos los derivados de Chromium)
        if (preg_match('/chrome\/([\d.]+)/i', $ua, $m))
            return 'Chrome ' . $m[1];

        // Safari (debe ir después de Chrome para evitar confusión con WebKit)
        if (preg_match('/version\/([\d.]+).*safari/i', $ua, $m))
            return 'Safari ' . $m[1];

        if (preg_match('/safari/i', $ua))
            return 'Safari';

        // Android WebView
        if (preg_match('/wv\)/i', $ua))
            return 'Android WebView';

        // Facebook in-app / Instagram in-app browsers
        if (preg_match('/fbav\/([\d.]+)/i', $ua, $m))
            return 'Facebook In-App ' . $m[1];

        if (preg_match('/instagram/i', $ua))
            return 'Instagram In-App';

        // Bots / Crawlers (para no contaminar estadísticas)
        if (preg_match('/(googlebot|bingbot|slurp|duckduckbot|baiduspider|yandexbot|sogou|exabot|facebot|ia_archiver)/i', $ua))
            return 'Bot/Crawler';

        return 'Unknown Browser';
    }

    /** Devuelve un array completo con dispositivo, OS y navegador. */
    private static function clientInfo(string $ua): array {
        return [
            'device'  => self::detectDevice($ua),
            'os'      => self::detectOS($ua),
            'browser' => self::detectBrowser($ua),
        ];
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: SESIÓN
    // ─────────────────────────────────────────────

    public static function logLogin(int $userId, string $ip, string $userAgent, ?int $sessionDuration = null): self {
        $info = self::clientInfo($userAgent);
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_LOGIN,
            'action'      => 'Inicio de sesión',
            'description' => 'El usuario inició sesión en el sistema',
            'ip_address'  => $ip,
            'user_agent'  => $userAgent,
            'data'        => [
                'login_time'       => now()->toDateTimeString(),
                'session_duration' => $sessionDuration,
                'device'           => $info['device'],
                'os'               => $info['os'],
                'browser'          => $info['browser'],
            ],
        ]);
    }

    public static function logLogout(int $userId, ?int $sessionDuration = null): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_LOGOUT,
            'action'      => 'Cierre de sesión',
            'description' => 'El usuario cerró sesión',
            'data'        => [
                'logout_time'      => now()->toDateTimeString(),
                'session_duration' => $sessionDuration,
            ],
        ]);
    }

    public static function logPasswordChanged(int $userId, string $ip, string $userAgent): self {
        $info = self::clientInfo($userAgent);
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_PASSWORD_CHANGED,
            'action'      => 'Contraseña cambiada',
            'description' => 'El usuario cambió su contraseña',
            'ip_address'  => $ip,
            'user_agent'  => $userAgent,
            'data'        => [
                'changed_at' => now()->toDateTimeString(),
                'device'     => $info['device'],
                'os'         => $info['os'],
                'browser'    => $info['browser'],
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: DASHBOARD
    // ─────────────────────────────────────────────

    public static function logDashboardAccessed(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_DASHBOARD_ACCESSED,
            'action'      => 'Dashboard visitado',
            'description' => 'El usuario accedió al dashboard principal',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: MI PERFIL
    // ─────────────────────────────────────────────

    public static function logProfileAccessed(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_PROFILE_ACCESSED,
            'action'      => 'Perfil visitado',
            'description' => 'El usuario visitó su perfil',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    public static function logProfileUpdated(int $userId, array $changedFields = []): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_PROFILE_UPDATED,
            'action'      => 'Perfil actualizado',
            'description' => 'El usuario actualizó su perfil',
            'data'        => [
                'updated_at'     => now()->toDateTimeString(),
                'changed_fields' => $changedFields,
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: MIS CURSOS
    // ─────────────────────────────────────────────

    public static function logCoursesListViewed(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COURSES_LIST_VIEWED,
            'action'      => 'Lista de cursos visitada',
            'description' => 'El usuario visitó la sección "Mis Cursos"',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    public static function logCourseAccessed(int $userId, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COURSE_ACCESSED,
            'action'      => 'Curso accedido',
            'description' => "El usuario accedió al curso: {$course->title}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title' => $course->title,
                'accessed_at'  => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCourseEnrollment(int $userId, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COURSE_ENROLLED,
            'action'      => 'Inscripción a curso',
            'description' => "Se inscribió en el curso: {$course->title}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title'    => $course->title,
                'course_price'    => $course->final_price,
                'enrollment_date' => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logLessonAccessed(int $userId, $lesson, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_LESSON_ACCESSED,
            'action'      => 'Lección accedida',
            'description' => "El usuario accedió a la lección: {$lesson->title}",
            'course_id'   => $course->id,
            'lesson_id'   => $lesson->id,
            'data'        => [
                'lesson_title' => $lesson->title,
                'course_title' => $course->title,
                'accessed_at'  => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logLessonCompleted(int $userId, $lesson, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_LESSON_COMPLETED,
            'action'      => 'Lección completada',
            'description' => "Completó la lección: {$lesson->title}",
            'course_id'   => $course->id,
            'lesson_id'   => $lesson->id,
            'data'        => [
                'lesson_title'    => $lesson->title,
                'course_title'    => $course->title,
                'completion_time' => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCourseCompleted(int $userId, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COURSE_COMPLETED,
            'action'      => 'Curso completado',
            'description' => "El usuario completó el curso: {$course->title}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title'    => $course->title,
                'completion_time' => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCourseSearched(int $userId, string $query, int $resultsCount = 0): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COURSE_SEARCHED,
            'action'      => 'Búsqueda de cursos',
            'description' => "El usuario buscó: \"{$query}\"",
            'data'        => [
                'query'         => $query,
                'results_count' => $resultsCount,
                'searched_at'   => now()->toDateTimeString(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: EXÁMENES
    // ─────────────────────────────────────────────

    public static function logExamsListViewed(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_EXAMS_LIST_VIEWED,
            'action'      => 'Lista de exámenes visitada',
            'description' => 'El usuario visitó la sección "Exámenes"',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    /**
     * Registra inicio, aprobación o reprobación de un examen.
     *
     * @param  bool|null  $passed  null = recién iniciado, true = aprobado, false = reprobado
     */
    public static function logExamAttempt(int $userId, $exam, ?float $score = null, ?bool $passed = null): self {
        if ($passed === null) {
            $type        = self::TYPE_EXAM_STARTED;
            $action      = 'Examen iniciado';
            $description = "Inició el examen: {$exam->title}";
        } elseif ($passed) {
            $type        = self::TYPE_EXAM_COMPLETED;
            $action      = 'Examen aprobado';
            $description = "Aprobó el examen: {$exam->title} con {$score} puntos";
        } else {
            $type        = self::TYPE_EXAM_FAILED;
            $action      = 'Examen reprobado';
            $description = "Reprobó el examen: {$exam->title} con {$score} puntos";
        }

        return self::create([
            'user_id'     => $userId,
            'type'        => $type,
            'action'      => $action,
            'description' => $description,
            'exam_id'     => $exam->id,
            'data'        => [
                'exam_title'  => $exam->title,
                'score'       => $score,
                'passed'      => $passed,
                'attempt_time'=> now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logExamRetried(int $userId, $exam): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_EXAM_RETRIED,
            'action'      => 'Examen reintentado',
            'description' => "El usuario reintentó el examen: {$exam->title}",
            'exam_id'     => $exam->id,
            'data'        => [
                'exam_title' => $exam->title,
                'retried_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: CERTIFICADOS
    // ─────────────────────────────────────────────

    public static function logCertificatesViewed(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_CERTIFICATES_VIEWED,
            'action'      => 'Certificados visitados',
            'description' => 'El usuario visitó la sección "Certificados"',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    public static function logCertificateEarned(int $userId, $certificate, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_CERTIFICATE_EARNED,
            'action'      => 'Certificado obtenido',
            'description' => "Obtuvo el certificado del curso: {$course->title}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title'     => $course->title,
                'certificate_code' => $certificate->certificate_code,
                'issue_date'       => $certificate->issue_date->toDateTimeString(),
            ],
        ]);
    }

    public static function logCertificateDownloaded(int $userId, $certificate, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_CERTIFICATE_DOWNLOAD,
            'action'      => 'Certificado descargado',
            'description' => "Descargó el certificado del curso: {$course->title}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title'     => $course->title,
                'certificate_code' => $certificate->certificate_code,
                'downloaded_at'    => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCertificateShared(int $userId, $certificate, $course, string $platform = 'link'): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_CERTIFICATE_SHARED,
            'action'      => 'Certificado compartido',
            'description' => "Compartió el certificado del curso: {$course->title} vía {$platform}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title'     => $course->title,
                'certificate_code' => $certificate->certificate_code,
                'platform'         => $platform,
                'shared_at'        => now()->toDateTimeString(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: MI PROGRESO
    // ─────────────────────────────────────────────

    public static function logProgressViewed(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_PROGRESS_VIEWED,
            'action'      => 'Progreso visitado',
            'description' => 'El usuario visitó la sección "Mi Progreso"',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: CARRITO & PAGOS
    // ─────────────────────────────────────────────

    public static function logCartViewed(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_CART_VIEWED,
            'action'      => 'Carrito visitado',
            'description' => 'El usuario visualizó su carrito de compras',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    public static function logCartAdded(int $userId, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_CART_ADDED,
            'action'      => 'Producto agregado al carrito',
            'description' => "Agregó al carrito: {$course->title}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title' => $course->title,
                'course_price' => $course->final_price,
                'added_at'     => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCartRemoved(int $userId, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_CART_REMOVED,
            'action'      => 'Producto eliminado del carrito',
            'description' => "Eliminó del carrito: {$course->title}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title' => $course->title,
                'removed_at'   => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCartCleared(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_CART_CLEARED,
            'action'      => 'Carrito vaciado',
            'description' => 'El usuario vació su carrito de compras',
            'data'        => ['cleared_at' => now()->toDateTimeString()],
        ]);
    }

    public static function logPaymentCompleted(int $userId, float $total, string $method, array $items = []): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_PAYMENT_COMPLETED,
            'action'      => 'Pago completado',
            'description' => "Completó un pago de S/ {$total} mediante {$method}",
            'data'        => [
                'total'        => $total,
                'method'       => $method,
                'items'        => $items,
                'completed_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logPaymentFailed(int $userId, float $total, string $method, string $reason = ''): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_PAYMENT_FAILED,
            'action'      => 'Pago fallido',
            'description' => "Falló el pago de S/ {$total} mediante {$method}",
            'data'        => [
                'total'     => $total,
                'method'    => $method,
                'reason'    => $reason,
                'failed_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: WISHLIST
    // ─────────────────────────────────────────────

    public static function logWishlistViewed(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_WISHLIST_VIEWED,
            'action'      => 'Wishlist visitada',
            'description' => 'El usuario visitó su lista de deseos',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    public static function logWishlistAdded(int $userId, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_WISHLIST_ADDED,
            'action'      => 'Agregado a wishlist',
            'description' => "Agregó a su lista de deseos: {$course->title}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title' => $course->title,
                'added_at'     => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logWishlistRemoved(int $userId, $course): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_WISHLIST_REMOVED,
            'action'      => 'Eliminado de wishlist',
            'description' => "Eliminó de su lista de deseos: {$course->title}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title' => $course->title,
                'removed_at'   => now()->toDateTimeString(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: MIS VENTAS (AFILIADO)
    // ─────────────────────────────────────────────

    public static function logAffiliateDashboardViewed(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_AFFILIATE_DASHBOARD_VIEWED,
            'action'      => 'Panel de afiliado visitado',
            'description' => 'El usuario visitó su panel de ventas / afiliados',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    public static function logAffiliateLinkCopied(int $userId, string $promoCode): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_AFFILIATE_LINK_COPIED,
            'action'      => 'Enlace de afiliado copiado',
            'description' => "El usuario copió su enlace de afiliado con código: {$promoCode}",
            'data'        => [
                'promo_code' => $promoCode,
                'copied_at'  => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logAffiliateSaleGenerated(int $userId, $course, float $commission): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_AFFILIATE_SALE_GENERATED,
            'action'      => 'Venta de afiliado generada',
            'description' => "Generó una venta del curso: {$course->title} con comisión S/ {$commission}",
            'course_id'   => $course->id,
            'data'        => [
                'course_title'   => $course->title,
                'commission'     => $commission,
                'generated_at'   => now()->toDateTimeString(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÓDULO: PANEL EMPRESA
    // ─────────────────────────────────────────────

    public static function logCompanyPanelAccessed(int $userId, ?string $planType = null): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COMPANY_PANEL_ACCESSED,
            'action'      => 'Panel empresa accedido',
            'description' => 'El usuario accedió al panel de administración de empresa',
            'data'        => [
                'plan_type'   => $planType,
                'accessed_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCompanyUsersManaged(int $userId): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COMPANY_USERS_MANAGED,
            'action'      => 'Gestión de usuarios de empresa',
            'description' => 'El usuario visitó la sección "Gestionar mis usuarios"',
            'data'        => ['accessed_at' => now()->toDateTimeString()],
        ]);
    }

    public static function logCompanyUsersEnrolled(int $userId, int $enrolledCount = 0, ?int $courseId = null): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COMPANY_USERS_ENROLLED,
            'action'      => 'Inscripción masiva de usuarios',
            'description' => "Inscribió {$enrolledCount} usuario(s) de su empresa",
            'course_id'   => $courseId,
            'data'        => [
                'enrolled_count' => $enrolledCount,
                'enrolled_at'    => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCompanyUserAdded(int $userId, int $newUserId, string $newUserEmail): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COMPANY_USER_ADDED,
            'action'      => 'Usuario de empresa añadido',
            'description' => "Añadió al usuario {$newUserEmail} a su empresa",
            'data'        => [
                'new_user_id'    => $newUserId,
                'new_user_email' => $newUserEmail,
                'added_at'       => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCompanyUserRemoved(int $userId, int $removedUserId, string $removedUserEmail): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COMPANY_USER_REMOVED,
            'action'      => 'Usuario de empresa eliminado',
            'description' => "Eliminó al usuario {$removedUserEmail} de su empresa",
            'data'        => [
                'removed_user_id'    => $removedUserId,
                'removed_user_email' => $removedUserEmail,
                'removed_at'         => now()->toDateTimeString(),
            ],
        ]);
    }

    public static function logCompanyReportViewed(int $userId, string $reportType = 'general'): self {
        return self::create([
            'user_id'     => $userId,
            'type'        => self::TYPE_COMPANY_REPORT_VIEWED,
            'action'      => 'Reporte de empresa visualizado',
            'description' => "El usuario visualizó el reporte: {$reportType}",
            'data'        => [
                'report_type' => $reportType,
                'viewed_at'   => now()->toDateTimeString(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  MÉTODO GENÉRICO
    // ─────────────────────────────────────────────

    public static function logGenericActivity(
        int $userId,
        string $type,
        string $action,
        string $description,
        array $data = [],
        $model = null
    ): self {
        $activityData = [
            'user_id'     => $userId,
            'type'        => $type,
            'action'      => $action,
            'description' => $description,
            'data'        => $data,
        ];

        if ($model) {
            $activityData['model_type'] = get_class($model);
            $activityData['model_id']   = $model->id;
        }

        return self::create($activityData);
    }

    // ─────────────────────────────────────────────
    //  SCOPES
    // ─────────────────────────────────────────────

    public function scopeForUser($query, int $userId) {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, string $type) {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, int $days = 7) {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeToday($query) {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query) {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query) {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeByModule($query, string $module) {
        $moduleTypes = [
            'session'     => [self::TYPE_LOGIN, self::TYPE_LOGOUT, self::TYPE_PASSWORD_CHANGED],
            'courses'     => [self::TYPE_COURSES_LIST_VIEWED, self::TYPE_COURSE_ACCESSED, self::TYPE_COURSE_ENROLLED, self::TYPE_LESSON_ACCESSED, self::TYPE_LESSON_COMPLETED, self::TYPE_COURSE_COMPLETED, self::TYPE_COURSE_SEARCHED],
            'exams'       => [self::TYPE_EXAMS_LIST_VIEWED, self::TYPE_EXAM_STARTED, self::TYPE_EXAM_COMPLETED, self::TYPE_EXAM_FAILED, self::TYPE_EXAM_RETRIED],
            'certificates'=> [self::TYPE_CERTIFICATES_VIEWED, self::TYPE_CERTIFICATE_EARNED, self::TYPE_CERTIFICATE_DOWNLOAD, self::TYPE_CERTIFICATE_SHARED],
            'progress'    => [self::TYPE_PROGRESS_VIEWED],
            'cart'        => [self::TYPE_CART_VIEWED, self::TYPE_CART_ADDED, self::TYPE_CART_REMOVED, self::TYPE_CART_CLEARED, self::TYPE_PAYMENT_COMPLETED, self::TYPE_PAYMENT_FAILED],
            'wishlist'    => [self::TYPE_WISHLIST_VIEWED, self::TYPE_WISHLIST_ADDED, self::TYPE_WISHLIST_REMOVED],
            'affiliate'   => [self::TYPE_AFFILIATE_DASHBOARD_VIEWED, self::TYPE_AFFILIATE_LINK_COPIED, self::TYPE_AFFILIATE_SALE_GENERATED],
            'company'     => [self::TYPE_COMPANY_PANEL_ACCESSED, self::TYPE_COMPANY_USERS_MANAGED, self::TYPE_COMPANY_USERS_ENROLLED, self::TYPE_COMPANY_USER_ADDED, self::TYPE_COMPANY_USER_REMOVED, self::TYPE_COMPANY_REPORT_VIEWED],
            'profile'     => [self::TYPE_PROFILE_ACCESSED, self::TYPE_PROFILE_UPDATED],
        ];

        return $query->whereIn('type', $moduleTypes[$module] ?? []);
    }

    // ─────────────────────────────────────────────
    //  ACCESSORS
    // ─────────────────────────────────────────────

    public function getFormattedDateAttribute(): string {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getIconAttribute(): string {
        return self::iconMap()[$this->type] ?? 'circle';
    }

    public function getColorAttribute(): string {
        return self::colorMap()[$this->type] ?? 'gray';
    }

    // ─────────────────────────────────────────────
    //  MAPAS ICONO / COLOR (centralizados)
    // ─────────────────────────────────────────────

    public static function iconMap(): array {
        return [
            // Sesión
            self::TYPE_LOGIN              => 'sign-in-alt',
            self::TYPE_LOGOUT             => 'sign-out-alt',
            self::TYPE_PASSWORD_CHANGED   => 'lock',
            // Dashboard
            self::TYPE_DASHBOARD_ACCESSED => 'tachometer-alt',
            // Perfil
            self::TYPE_PROFILE_ACCESSED   => 'user',
            self::TYPE_PROFILE_UPDATED    => 'user-edit',
            // Cursos
            self::TYPE_COURSES_LIST_VIEWED=> 'book-open',
            self::TYPE_COURSE_ACCESSED    => 'play',
            self::TYPE_COURSE_ENROLLED    => 'book',
            self::TYPE_LESSON_ACCESSED    => 'eye',
            self::TYPE_LESSON_COMPLETED   => 'check-circle',
            self::TYPE_COURSE_COMPLETED   => 'graduation-cap',
            self::TYPE_COURSE_SEARCHED    => 'search',
            // Exámenes
            self::TYPE_EXAMS_LIST_VIEWED  => 'list-alt',
            self::TYPE_EXAM_STARTED       => 'file-alt',
            self::TYPE_EXAM_COMPLETED     => 'file-check',
            self::TYPE_EXAM_FAILED        => 'file-times',
            self::TYPE_EXAM_RETRIED       => 'redo',
            // Certificados
            self::TYPE_CERTIFICATES_VIEWED  => 'award',
            self::TYPE_CERTIFICATE_EARNED   => 'certificate',
            self::TYPE_CERTIFICATE_DOWNLOAD => 'download',
            self::TYPE_CERTIFICATE_SHARED   => 'share-alt',
            // Progreso
            self::TYPE_PROGRESS_VIEWED    => 'chart-line',
            // Carrito
            self::TYPE_CART_VIEWED        => 'shopping-cart',
            self::TYPE_CART_ADDED         => 'cart-plus',
            self::TYPE_CART_REMOVED       => 'cart-arrow-down',
            self::TYPE_CART_CLEARED       => 'trash',
            self::TYPE_PAYMENT_COMPLETED  => 'credit-card',
            self::TYPE_PAYMENT_FAILED     => 'times-circle',
            // Wishlist
            self::TYPE_WISHLIST_VIEWED    => 'heart',
            self::TYPE_WISHLIST_ADDED     => 'heart',
            self::TYPE_WISHLIST_REMOVED   => 'heart-broken',
            // Afiliado
            self::TYPE_AFFILIATE_DASHBOARD_VIEWED => 'users',
            self::TYPE_AFFILIATE_LINK_COPIED      => 'link',
            self::TYPE_AFFILIATE_SALE_GENERATED   => 'dollar-sign',
            // Empresa
            self::TYPE_COMPANY_PANEL_ACCESSED  => 'building',
            self::TYPE_COMPANY_USERS_MANAGED   => 'user-cog',
            self::TYPE_COMPANY_USERS_ENROLLED  => 'user-plus',
            self::TYPE_COMPANY_USER_ADDED      => 'user-plus',
            self::TYPE_COMPANY_USER_REMOVED    => 'user-minus',
            self::TYPE_COMPANY_REPORT_VIEWED   => 'chart-bar',
        ];
    }

    public static function colorMap(): array {
        return [
            // Sesión
            self::TYPE_LOGIN              => 'green',
            self::TYPE_LOGOUT             => 'gray',
            self::TYPE_PASSWORD_CHANGED   => 'red',
            // Dashboard
            self::TYPE_DASHBOARD_ACCESSED => 'blue',
            // Perfil
            self::TYPE_PROFILE_ACCESSED   => 'indigo',
            self::TYPE_PROFILE_UPDATED    => 'purple',
            // Cursos
            self::TYPE_COURSES_LIST_VIEWED=> 'blue',
            self::TYPE_COURSE_ACCESSED    => 'blue',
            self::TYPE_COURSE_ENROLLED    => 'blue',
            self::TYPE_LESSON_ACCESSED    => 'sky',
            self::TYPE_LESSON_COMPLETED   => 'green',
            self::TYPE_COURSE_COMPLETED   => 'emerald',
            self::TYPE_COURSE_SEARCHED    => 'gray',
            // Exámenes
            self::TYPE_EXAMS_LIST_VIEWED  => 'yellow',
            self::TYPE_EXAM_STARTED       => 'yellow',
            self::TYPE_EXAM_COMPLETED     => 'green',
            self::TYPE_EXAM_FAILED        => 'red',
            self::TYPE_EXAM_RETRIED       => 'orange',
            // Certificados
            self::TYPE_CERTIFICATES_VIEWED  => 'amber',
            self::TYPE_CERTIFICATE_EARNED   => 'yellow',
            self::TYPE_CERTIFICATE_DOWNLOAD => 'teal',
            self::TYPE_CERTIFICATE_SHARED   => 'cyan',
            // Progreso
            self::TYPE_PROGRESS_VIEWED    => 'emerald',
            // Carrito
            self::TYPE_CART_VIEWED        => 'blue',
            self::TYPE_CART_ADDED         => 'blue',
            self::TYPE_CART_REMOVED       => 'orange',
            self::TYPE_CART_CLEARED       => 'red',
            self::TYPE_PAYMENT_COMPLETED  => 'green',
            self::TYPE_PAYMENT_FAILED     => 'red',
            // Wishlist
            self::TYPE_WISHLIST_VIEWED    => 'pink',
            self::TYPE_WISHLIST_ADDED     => 'pink',
            self::TYPE_WISHLIST_REMOVED   => 'gray',
            // Afiliado
            self::TYPE_AFFILIATE_DASHBOARD_VIEWED => 'purple',
            self::TYPE_AFFILIATE_LINK_COPIED      => 'indigo',
            self::TYPE_AFFILIATE_SALE_GENERATED   => 'green',
            // Empresa
            self::TYPE_COMPANY_PANEL_ACCESSED  => 'violet',
            self::TYPE_COMPANY_USERS_MANAGED   => 'violet',
            self::TYPE_COMPANY_USERS_ENROLLED  => 'violet',
            self::TYPE_COMPANY_USER_ADDED      => 'green',
            self::TYPE_COMPANY_USER_REMOVED    => 'red',
            self::TYPE_COMPANY_REPORT_VIEWED   => 'slate',
        ];
    }
}