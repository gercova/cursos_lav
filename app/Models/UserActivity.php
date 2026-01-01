<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model {
    use HasFactory;

    protected $table        = 'user_activities';
    protected $primaryKey   = 'id';

    protected $fillable     = [
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
        'model_id'
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Tipos de actividad predefinidos
    const TYPE_LOGIN = 'login';
    const TYPE_LOGOUT = 'logout';
    const TYPE_COURSE_ENROLLED = 'course_enrolled';
    const TYPE_LESSON_COMPLETED = 'lesson_completed';
    const TYPE_EXAM_STARTED = 'exam_started';
    const TYPE_EXAM_COMPLETED = 'exam_completed';
    const TYPE_CERTIFICATE_EARNED = 'certificate_earned';
    const TYPE_PROFILE_UPDATED = 'profile_updated';
    const TYPE_PAYMENT_COMPLETED = 'payment_completed';
    const TYPE_CART_ADDED = 'cart_added';
    const TYPE_WISHLIST_ADDED = 'wishlist_added';
    const TYPE_COURSE_ACCESSED = 'course_accessed';
    const TYPE_PASSWORD_CHANGED = 'password_changed';

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

    // Métodos estáticos para registrar actividades comunes
    public static function logLogin($userId, $ip, $userAgent) {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_LOGIN,
            'action' => 'Inicio de sesión',
            'description' => 'El usuario inició sesión en el sistema',
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'data' => [
                'login_time' => now()->toDateTimeString(),
                'device' => self::detectDevice($userAgent),
                'browser' => self::detectBrowser($userAgent)
            ]
        ]);
    }

    public static function logLogout($userId) {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_LOGOUT,
            'action' => 'Cierre de sesión',
            'description' => 'El usuario cerró sesión',
            'data' => [
                'logout_time' => now()->toDateTimeString()
            ]
        ]);
    }

    public static function logCourseEnrollment($userId, $course) {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_COURSE_ENROLLED,
            'action' => 'Inscripción a curso',
            'description' => "Se inscribió en el curso: {$course->title}",
            'course_id' => $course->id,
            'data' => [
                'course_title' => $course->title,
                'course_price' => $course->final_price,
                'enrollment_date' => now()->toDateTimeString()
            ]
        ]);
    }

    public static function logLessonCompleted($userId, $lesson, $course) {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_LESSON_COMPLETED,
            'action' => 'Lección completada',
            'description' => "Completó la lección: {$lesson->title}",
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'data' => [
                'lesson_title' => $lesson->title,
                'course_title' => $course->title,
                'completion_time' => now()->toDateTimeString()
            ]
        ]);
    }

    public static function logExamAttempt($userId, $exam, $score, $passed) {
        return self::create([
            'user_id'       => $userId,
            'type'          => $passed ? self::TYPE_EXAM_COMPLETED : self::TYPE_EXAM_STARTED,
            'action'        => $passed ? 'Examen aprobado' : 'Examen iniciado',
            'description'   => $passed
                ? "Aprobó el examen: {$exam->title} con {$score} puntos"
                : "Inició el examen: {$exam->title}",
            'exam_id' => $exam->id,
            'data' => [
                'exam_title'    => $exam->title,
                'score'         => $score,
                'passed'        => $passed,
                'attempt_time'  => now()->toDateTimeString()
            ]
        ]);
    }

    public static function logCertificateEarned($userId, $certificate, $course) {
        return self::create([
            'user_id'       => $userId,
            'type'          => self::TYPE_CERTIFICATE_EARNED,
            'action'        => 'Certificado obtenido',
            'description'   => "Obtuvo el certificado del curso: {$course->title}",
            'course_id'     => $course->id,
            'data' => [
                'course_title'      => $course->title,
                'certificate_code'  => $certificate->certificate_code,
                'issue_date'        => $certificate->issue_date->toDateTimeString()
            ]
        ]);
    }

    public static function logGenericActivity($userId, $type, $action, $description, $data = [], $model = null) {
        $activityData = [
            'user_id'       => $userId,
            'type'          => $type,
            'action'        => $action,
            'description'   => $description,
            'data'          => $data
        ];

        if ($model) {
            $activityData['model_type'] = get_class($model);
            $activityData['model_id'] = $model->id;
        }

        return self::create($activityData);
    }

    // Métodos de ayuda para detección
    private static function detectDevice($userAgent) {
        if (preg_match('/(android|iphone|ipad|mobile)/i', $userAgent)) {
            return 'Mobile';
        } elseif (preg_match('/(tablet|ipad)/i', $userAgent)) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    private static function detectBrowser($userAgent) {
        if (preg_match('/chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/edge/i', $userAgent)) {
            return 'Edge';
        } else {
            return 'Unknown';
        }
    }

    // Scopes para consultas comunes
    public function scopeForUser($query, $userId) {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type) {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 7) {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeToday($query) {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query) {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query) {
        return $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
    }

    // Métodos de acceso
    public function getFormattedDateAttribute() {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getIconAttribute() {
        $icons = [
            self::TYPE_LOGIN                => 'sign-in-alt',
            self::TYPE_LOGOUT               => 'sign-out-alt',
            self::TYPE_COURSE_ENROLLED      => 'book',
            self::TYPE_LESSON_COMPLETED     => 'check-circle',
            self::TYPE_EXAM_STARTED         => 'file-alt',
            self::TYPE_EXAM_COMPLETED       => 'file-check',
            self::TYPE_CERTIFICATE_EARNED   => 'certificate',
            self::TYPE_PROFILE_UPDATED      => 'user-edit',
            self::TYPE_PAYMENT_COMPLETED    => 'credit-card',
            self::TYPE_CART_ADDED           => 'shopping-cart',
            self::TYPE_WISHLIST_ADDED       => 'heart',
            self::TYPE_COURSE_ACCESSED      => 'play',
            self::TYPE_PASSWORD_CHANGED     => 'lock'
        ];

        return $icons[$this->type] ?? 'circle';
    }

    public function getColorAttribute() {
        $colors = [
            self::TYPE_LOGIN                => 'green',
            self::TYPE_LOGOUT               => 'gray',
            self::TYPE_COURSE_ENROLLED      => 'blue',
            self::TYPE_LESSON_COMPLETED     => 'green',
            self::TYPE_EXAM_STARTED         => 'yellow',
            self::TYPE_EXAM_COMPLETED       => 'green',
            self::TYPE_CERTIFICATE_EARNED   => 'yellow',
            self::TYPE_PROFILE_UPDATED      => 'purple',
            self::TYPE_PAYMENT_COMPLETED    => 'green',
            self::TYPE_CART_ADDED           => 'blue',
            self::TYPE_WISHLIST_ADDED       => 'pink',
            self::TYPE_COURSE_ACCESSED      => 'blue',
            self::TYPE_PASSWORD_CHANGED     => 'red'
        ];

        return $colors[$this->type] ?? 'gray';
    }
}
