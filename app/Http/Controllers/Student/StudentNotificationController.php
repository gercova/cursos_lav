<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentNotificationController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    // Vista de notificaciones
    public function index(): View {
        return view('student.notification');
    }

    // API para obtener notificaciones
    public function apiIndex(Request $request): JsonResponse {
        $user = Auth::user();

        // Obtener notificaciones del usuario
        $notifications = $user->notifications()
            ->active()
            ->latest()
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id'            => $notification->id,
                    'title'         => $notification->title,
                    'message'       => $notification->message,
                    'type'          => $notification->type,
                    'link'          => $notification->link,
                    'icon'          => $notification->icon,
                    'color'         => $notification->color,
                    'read_at'       => $notification->read_at,
                    'time'          => $notification->created_at->diffForHumans(),
                    'created_at'    => $notification->created_at->format('d/m/Y H:i'),
                ];
            });

        // Contador de no leídas
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'success'       => true,
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
            'total'         => $notifications->count(),
        ]);
    }

    // Marcar notificación como leída
    public function markAsRead($id): JsonResponse {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída',
        ]);
    }

    // Marcar notificación como no leída
    public function markAsUnread($id): JsonResponse {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como no leída',
        ]);
    }

    // Marcar todas como leídas
    public function markAllAsRead(): JsonResponse {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas',
        ]);
    }

    // Eliminar notificación
    public function destroy($id): JsonResponse {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada',
        ]);
    }

    // Eliminar todas las notificaciones
    public function clearAll(): JsonResponse {
        Auth::user()->notifications()->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones eliminadas',
        ]);
    }
}
