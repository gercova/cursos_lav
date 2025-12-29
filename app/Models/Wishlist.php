<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = 'wishlists';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'course_id',
        'added_at',
        'notes'
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    // Obtener items de la lista de deseos con detalles
    public static function getItems($userId) {
        return self::where('user_id', $userId)
            ->with(['course' => function ($query) {
                $query->with('category', 'instructor');
            }])
            ->latest('added_at')
            ->get()
            ->map(function ($item) {
                $course = $item->course;
                return [
                    'id' => $item->id,
                    'course_id' => $course->id,
                    'title' => $course->title,
                    'short_description' => $course->short_description,
                    'description' => $course->description,
                    'image_url' => $course->image_url,
                    'price' => $course->price,
                    'promotion_price' => $course->promotion_price,
                    'is_on_promotion' => $course->is_on_promotion,
                    'category_name' => $course->category->name ?? 'Sin categoría',
                    'instructor_name' => $course->instructor->names ?? 'Instructor',
                    'rating' => 4.8, // Puedes agregar ratings reales
                    'students_count' => $course->students_count ?? 125,
                    'duration' => $course->duration ?? '10',
                    'level' => $course->level ?? 'Intermedio',
                    'added_date' => $item->added_at->format('d/m/Y'),
                    'added_at' => $item->added_at
                ];
            });
    }

    // Verificar si un curso está en la lista de deseos
    public static function isInWishlist($userId, $courseId): bool {
        return self::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }

    // Contar items en la lista de deseos
    public static function countItems($userId): int {
        return self::where('user_id', $userId)->count();
    }

    // Limpiar la lista de deseos
    public static function clear($userId): bool {
        return self::where('user_id', $userId)->delete();
    }

    // Obtener cursos recomendados basados en la lista de deseos
    public static function getRecommendedCourses($userId, $limit = 3) {
        // Obtener categorías de los cursos en la lista de deseos
        $userWishlistCategories = self::where('user_id', $userId)
            ->with('course.category')
            ->get()
            ->pluck('course.category_id')
            ->filter()
            ->unique()
            ->toArray();

        // Si no hay categorías, devolver cursos populares
        if (empty($userWishlistCategories)) {
            return Course::with('instructor', 'category')
                ->where('is_active', true)
                ->orderBy('students_count', 'desc')
                ->limit($limit)
                ->get();
        }

        // Obtener cursos de las mismas categorías que no están en la lista de deseos
        return Course::with('instructor', 'category')
            ->where('is_active', true)
            ->whereIn('category_id', $userWishlistCategories)
            ->whereNotIn('id', function($query) use ($userId) {
                $query->select('course_id')
                    ->from('wishlists')
                    ->where('user_id', $userId);
            })
            ->orderBy('students_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
