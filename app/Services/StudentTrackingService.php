<?php
// app/Services/StudentTrackingService.php

namespace App\Services;

use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StudentTrackingService
{
    protected $user;
    protected $days = 30; // Por defecto, últimos 30 días

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function setDays($days): self {
        $this->days = $days;
        return $this;
    }

    /**
     * Obtener sesiones por día
     */
    public function getSessionsByDay(): array {
        $activities = UserActivity::where('user_id', $this->user->id)
            ->where('type', UserActivity::TYPE_LOGIN)
            ->whereDate('created_at', '>=', now()->subDays($this->days))
            ->get()
            ->groupBy(function($activity) {
                return $activity->created_at->format('Y-m-d');
            });

        $data = [];
        for ($i = $this->days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data[$date] = isset($activities[$date]) ? $activities[$date]->count() : 0;
        }

        return [
            'labels'    => array_keys($data),
            'data'      => array_values($data)
        ];
    }

    /**
     * Obtener tiempo promedio de sesión
     */
    public function getAverageSessionTime(): float {
        $sessions = UserActivity::where('user_id', $this->user->id)
            ->where('type', UserActivity::TYPE_LOGIN)
            ->whereDate('created_at', '>=', now()->subDays($this->days))
            ->get();

        $totalDuration  = 0;
        $sessionCount   = 0;

        foreach ($sessions as $login) {
            // Buscar logout correspondiente (mismo día)
            $logout = UserActivity::where('user_id', $this->user->id)
                ->where('type', UserActivity::TYPE_LOGOUT)
                ->whereDate('created_at', $login->created_at->format('Y-m-d'))
                ->where('created_at', '>', $login->created_at)
                ->first();

            if ($logout) {
                $duration = $logout->created_at->diffInMinutes($login->created_at);
                $totalDuration += $duration;
                $sessionCount++;
            }
        }

        return $sessionCount > 0 ? round($totalDuration / $sessionCount, 2) : 0;
    }

    /**
     * Obtener progreso de cursos
     */
    public function getCourseProgress(): Collection
    {
        return $this->user->enrollments()
            ->with('course')
            ->select('course_id', 'progress', 'status', 'enrolled_at', 'completed_at')
            ->get()
            ->map(function($enrollment) {
                return [
                    'course_id'     => $enrollment->course_id,
                    'course_title'  => $enrollment->course->title ?? 'Curso no disponible',
                    'progress'      => $enrollment->progress,
                    'status'        => $enrollment->status,
                    'enrolled_at'   => $enrollment->enrolled_at?->format('d/m/Y'),
                    'completed_at'  => $enrollment->completed_at?->format('d/m/Y'),
                    'duration_days' => $enrollment->enrolled_at ? 
                        $enrollment->completed_at ? 
                            $enrollment->enrolled_at->diffInDays($enrollment->completed_at) : 
                            $enrollment->enrolled_at->diffInDays(now()) : 
                        0
                ];
            });
    }

    /**
     * Obtener actividad por tipo
     */
    public function getActivityByType(): array {
        $activities = UserActivity::where('user_id', $this->user->id)
            ->whereDate('created_at', '>=', now()->subDays($this->days))
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        return [
            'labels' => array_keys($activities->toArray()),
            'data' => array_values($activities->toArray())
        ];
    }

    /**
     * Obtener horas activas del día
     */
    public function getActiveHours(): array {
        $activities = UserActivity::where('user_id', $this->user->id)
            ->whereDate('created_at', '>=', now()->subDays($this->days))
            ->get()
            ->groupBy(function($activity) {
                return $activity->created_at->format('H:00');
            });

        $data = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourLabel = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
            $data[$hourLabel] = isset($activities[$hourLabel]) ? $activities[$hourLabel]->count() : 0;
        }

        return [
            'labels' => array_keys($data),
            'data' => array_values($data)
        ];
    }

    /**
     * Obtener dispositivos utilizados
     */
    public function getDevicesUsed(): array {
        $activities = UserActivity::where('user_id', $this->user->id)
            ->whereDate('created_at', '>=', now()->subDays($this->days))
            ->whereNotNull('data')
            ->get()
            ->map(function($activity) {
                $data = $activity->data;
                return isset($data['device']) ? $data['device'] : 'Desconocido';
            })
            ->groupBy(function($device) {
                return $device;
            });

        return [
            'labels' => $activities->keys()->toArray(),
            'data' => $activities->map->count()->values()->toArray()
        ];
    }

    /**
     * Obtener estadísticas generales
     */
    public function getOverallStats(): array {
        return [
            'total_sessions'        => UserActivity::where('user_id', $this->user->id)
                ->where('type', UserActivity::TYPE_LOGIN)
                ->whereDate('created_at', '>=', now()->subDays($this->days))
                ->count(),
            'average_session_time'  => $this->getAverageSessionTime(),
            'total_activities'      => UserActivity::where('user_id', $this->user->id)
                ->whereDate('created_at', '>=', now()->subDays($this->days))
                ->count(),
            'active_days'           => UserActivity::where('user_id', $this->user->id)
                ->whereDate('created_at', '>=', now()->subDays($this->days))
                ->distinct('created_at')
                ->count('created_at'),
            'last_login'            => UserActivity::where('user_id', $this->user->id)
                ->where('type', UserActivity::TYPE_LOGIN)
                ->latest()
                ->first()?->created_at?->format('d/m/Y H:i'),
            'most_active_hour'      => $this->getMostActiveHour()
        ];
    }

    /**
     * Obtener hora más activa
     */
    private function getMostActiveHour(): string {
        $activeHours = $this->getActiveHours();
        $maxIndex = array_search(max($activeHours['data']), $activeHours['data']);
        
        return $activeHours['labels'][$maxIndex] ?? 'N/A';
    }
}