<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Wishlist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller {


    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function index(): View {
        $userId = Auth::id();

        // Obtener items de la lista de deseos
        $wishlistItems = Wishlist::getItems($userId);

        // Obtener cursos recomendados
        $recommendedCourses = Wishlist::getRecommendedCourses($userId, 6);

        // Obtener cursos populares para estado vacío
        $popularCourses = Course::with('instructor', 'category')
            ->withCount('enrollments')
            ->where('is_active', true)
            ->orderBy('enrollments_count', 'desc')
            ->limit(3)
            ->get();

        return view('student.wishlist', compact(
            'wishlistItems',
            'recommendedCourses',
            'popularCourses'
        ));
    }

    public function add(Request $request): JsonResponse {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $userId = Auth::id();
        $courseId = $request->course_id;

        // Verificar si ya está en la lista de deseos
        if (Wishlist::isInWishlist($userId, $courseId)) {
            return response()->json([
                'success' => false,
                'message' => 'Este curso ya está en tu lista de deseos'
            ], 400);
        }

        // Verificar si el usuario ya está inscrito en el curso
        $enrolled = Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        if ($enrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Ya estás inscrito en este curso'
            ], 400);
        }

        // Agregar a la lista de deseos
        Wishlist::create([
            'user_id'   => $userId,
            'course_id' => $courseId,
            'added_at'  => now()
        ]);

        // Actualizar estadísticas del curso (opcional)
        // Course::where('id', $courseId)->increment('wishlist_count');

        return response()->json([
            'success'   => true,
            'message'   => 'Curso agregado a tu lista de deseos',
            'count'     => Wishlist::countItems($userId)
        ]);
    }

    public function remove($courseId): JsonResponse {
        $userId = Auth::id();

        $deleted = Wishlist::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success'   => true,
                'message'   => 'Curso eliminado de tu lista de deseos',
                'count'     => Wishlist::countItems($userId)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el curso'
        ], 400);
    }

    public function clearAll(): JsonResponse {
        $userId = Auth::id();

        $deleted = Wishlist::where('user_id', $userId)->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Lista de deseos vaciada correctamente'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al vaciar la lista de deseos'
        ], 400);
    }

    public function count(): JsonResponse {
        $userId = Auth::id();
        $count  = Wishlist::countItems($userId);

        return response()->json([
            'count' => $count
        ]);
    }

    public function check($courseId): JsonResponse {
        $userId = Auth::id();
        $isInWishlist = Wishlist::isInWishlist($userId, $courseId);

        return response()->json([
            'in_wishlist' => $isInWishlist
        ]);
    }

    public function toggle(Request $request): JsonResponse {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $userId = Auth::id();
        $courseId = $request->course_id;

        // Verificar si ya está en la lista de deseos
        $exists = Wishlist::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        if ($exists) {
            // Eliminar de la lista de deseos
            Wishlist::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->delete();

            $action = 'removed';
        } else {
            // Agregar a la lista de deseos
            Wishlist::create([
                'user_id'   => $userId,
                'course_id' => $courseId,
                'added_at'  => now()
            ]);

            $action = 'added';
        }

        return response()->json([
            'success'   => true,
            'action'    => $action,
            'count'     => Wishlist::countItems($userId)
        ]);
    }
}
