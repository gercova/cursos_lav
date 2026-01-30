<?php

namespace App\Traits;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait ActivityLogger
{
    /**
     * Registrar actividad automáticamente
     */
    protected static function bootActivityLogger()
    {
        foreach (static::getModelEvents() as $event) {
            static::$event(function ($model) use ($event) {
                if (Auth::check()) {
                    $model->logActivity($event);
                }
            });
        }
    }

    /**
     * Obtener eventos del modelo para monitorear
     */
    protected static function getModelEvents() {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created',
            'updated',
            'deleted'
        ];
    }

    /**
     * Registrar una actividad
     */
    public function logActivity($event) {
        $description = $this->getActivityDescription($event);

        if (!$description) {
            return;
        }

        UserActivity::logGenericActivity(
            Auth::id(),
            $this->getActivityType($event),
            $this->getActivityAction($event),
            $description,
            $this->getActivityData($event),
            $this
        );
    }

    /**
     * Obtener descripción de la actividad
     */
    protected function getActivityDescription($event) {
        $modelName = class_basename($this);

        $descriptions = [
            'created' => ":user creó un nuevo {$this->getModelName()} :title",
            'updated' => ":user actualizó el {$this->getModelName()} :title",
            'deleted' => ":user eliminó el {$this->getModelName()} :title",
        ];

        return strtr($descriptions[$event] ?? '', [
            ':user'     => Auth::user()->names,
            ':title'    => $this->getActivityTitle(),
            ':model'    => $modelName
        ]);
    }

    /**
     * Obtener tipo de actividad
     */
    protected function getActivityType($event) {
        $modelName = strtolower(class_basename($this));
        return "{$modelName}_{$event}";
    }

    /**
     * Obtener acción de la actividad
     */
    protected function getActivityAction($event) {
        $actions = [
            'created' => 'Creación',
            'updated' => 'Actualización',
            'deleted' => 'Eliminación'
        ];

        return $actions[$event] ?? 'Acción';
    }

    /**
     * Obtener datos adicionales para la actividad
     */
    protected function getActivityData($event) {
        $data = [
            'model_type'    => get_class($this),
            'model_id'      => $this->getKey(),
            'event'         => $event,
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::userAgent(),
            'timestamp'     => now()->toDateTimeString()
        ];

        // Agregar cambios si es una actualización
        if ($event === 'updated') {
            $data['changes'] = $this->getChanges();
            $data['original'] = $this->getOriginal();
        }

        return $data;
    }

    /**
     * Obtener nombre amigable del modelo
     */
    protected function getModelName() {
        $names = [
            'Course'        => 'curso',
            'Lesson'        => 'lección',
            'Exam'          => 'examen',
            'Certificate'   => 'certificado',
            'Enrollment'    => 'inscripción',
            'Cart'          => 'carrito',
            'Wishlist'      => 'lista de deseos',
            'User'          => 'usuario'
        ];

        return $names[class_basename($this)] ?? strtolower(class_basename($this));
    }

    /**
     * Obtener título para la actividad
     */
    protected function getActivityTitle() {
        if (isset($this->title)) {
            return $this->title;
        }

        if (isset($this->name)) {
            return $this->name;
        }

        if (isset($this->subject)) {
            return $this->subject;
        }

        return 'Sin título';
    }
}
