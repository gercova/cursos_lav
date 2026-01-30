<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService {

    // Tipos de notificaciones
    const TYPE_NEW_COURSE = 'new_course';
    const TYPE_PAYMENT_PENDING = 'payment_pending';
    const TYPE_EXAM_PENDING = 'exam_pending';
    const TYPE_COURSE_COMPLETED = 'course_completed';
    const TYPE_PAYMENT_APPROVED = 'payment_approved';
    const TYPE_CERTIFICATE_READY = 'certificate_ready';

    // Crear notificación
    public static function create(User $user, string $type, string $title, string $message, array $data = [], string $link = null) {
        $config = self::getNotificationConfig($type);

        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'link' => $link,
            'icon' => $config['icon'],
            'color' => $config['color'],
        ]);
    }

    // Configuración por tipo
    private static function getNotificationConfig(string $type): array {
        $configs = [
            self::TYPE_NEW_COURSE => [
                'icon' => 'book',
                'color' => 'blue',
            ],
            self::TYPE_PAYMENT_PENDING => [
                'icon' => 'credit-card',
                'color' => 'yellow',
            ],
            self::TYPE_EXAM_PENDING => [
                'icon' => 'file-alt',
                'color' => 'red',
            ],
            self::TYPE_COURSE_COMPLETED => [
                'icon' => 'check-circle',
                'color' => 'green',
            ],
            self::TYPE_PAYMENT_APPROVED => [
                'icon' => 'check-circle',
                'color' => 'green',
            ],
            self::TYPE_CERTIFICATE_READY => [
                'icon' => 'certificate',
                'color' => 'purple',
            ],
        ];

        return $configs[$type] ?? ['icon' => 'bell', 'color' => 'blue'];
    }

    // Notificación de nuevo curso disponible
    public static function sendNewCourseNotification(User $user, $course) {
        return self::create(
            $user,
            self::TYPE_NEW_COURSE,
            'Nuevo curso disponible',
            "El curso '{$course->title}' ya está disponible. ¡Inscríbete ahora!",
            ['course_id' => $course->id],
            route('course.show', $course->id)
        );
    }

    // Notificación de pago pendiente
    public static function sendPaymentPendingNotification(User $user, $payment) {
        return self::create(
            $user,
            self::TYPE_PAYMENT_PENDING,
            'Pago pendiente',
            'Tienes un pago pendiente por realizar. Completa el proceso para acceder al curso.',
            ['payment_id' => $payment->id],
            route('payment.cip-instructions', $payment->id)
        );
    }

    // Notificación de examen pendiente
    public static function sendExamPendingNotification(User $user, $course) {
        return self::create(
            $user,
            self::TYPE_EXAM_PENDING,
            'Examen pendiente',
            "El curso '{$course->title}' ha sido completado. ¡Realiza el examen para obtener tu certificado!",
            ['course_id' => $course->id],
            route('student.exams')
        );
    }

    // Notificación de curso completado
    public static function sendCourseCompletedNotification(User $user, $course) {
        return self::create(
            $user,
            self::TYPE_COURSE_COMPLETED,
            '¡Curso completado!',
            "Felicidades, has completado el curso '{$course->title}'. Ahora puedes realizar el examen final.",
            ['course_id' => $course->id],
            route('student.exams')
        );
    }

    // Notificación de pago aprobado
    public static function sendPaymentApprovedNotification(User $user, $payment) {
        $courseNames = $payment->enrollment->courses->pluck('title')->join(', ');

        return self::create(
            $user,
            self::TYPE_PAYMENT_APPROVED,
            'Pago aprobado',
            "Tu pago ha sido aprobado. Ya puedes acceder a tus cursos: {$courseNames}",
            ['payment_id' => $payment->id],
            route('student.my-courses')
        );
    }
}
