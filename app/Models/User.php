<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\StudentActivityLogger;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {

    use HasApiTokens, HasFactory, Notifiable, StudentActivityLogger, HasRoles;

    protected $table        = 'users';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'dni',
        'names',
        'email',
        'password',
        'country_code',
        'phone',
        'nationality',
        'ubigeo',
        'address',
        'profession',
        'role',
        'email_verified_at',
        'profile_photo',
        'code',
        'promotion_price_is_active',
        'is_active',
        'last_login_at',
        'courses_sold_count', // Campo nuevo agregado
        'total_commission',   // Campo nuevo agregado
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'     => 'datetime',
        'last_login_at'         => 'datetime',
        'password'              => 'hashed',
        'courses_sold_count'    => 'integer',
        'total_commission'      => 'decimal:2',
    ];

    protected $appends = ['profile_photo_url'];

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isStudent() {
        return $this->role === 'student';
    }

    public function isInstructor() {
        return $this->role === 'instructor';
    }

    public function isBusiness() {
        return $this->role === 'business';
    }

    public function enrollments(): HasMany {
        return $this->hasMany(Enrollment::class, 'user_id', 'id');
    }

    // Cursos que el usuario estudia
    public function studentCourses(): BelongsToMany {
        return $this->belongsToMany(Course::class, 'enrollments')
        ->withPivot('status', 'progress', 'enrolled_at');
    }

    public function courses(): HasMany {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function certificates(): HasMany {
        return $this->hasMany(Certificate::class, 'user_id', 'id');
    }

    public function examAttempts(): HasMany {
        return $this->hasMany(ExamAttempt::class, 'user_id', 'id');
    }

    public function cartItems(): HasMany {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    public function notifications(): HasMany {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications() {
        return $this->notifications()->unread();
    }

    public function promotedSales(): HasMany {
        return $this->hasMany(CourseSale::class, 'user_id');
    }

    public function companyPolicies(): HasOne {
        return $this->hasOne(CompanyPolicy::class, 'user_id', 'id');
    }

    /**
     * Relación con cursos comprados usando códigos de otros usuarios
     */
    public function purchasesWithCode(): HasMany {
        return $this->hasMany(CourseSale::class, 'buyer_id');
    }

    /**
     * Verificar si el usuario tiene código de promoción
     */
    public function hasPromotionCode(): bool {
        return !empty($this->code);
    }

    /**
     * Obtener URL de afiliado
     */
    public function getAffiliateUrlAttribute(): string {
        return route('cursos-promo', ['code' => $this->code]);
    }

    /**
     * Incrementar contador de ventas
     */
    public function incrementSalesCount(): void {
        $this->increment('courses_sold_count');
        $this->save();
    }

    /**
     * Agregar comisión
     */
    public function addCommission(float $amount): void {
        $this->total_commission = ($this->total_commission ?? 0) + $amount;
        $this->save();
    }

    // protected function profilePhotoUrl(): Attribute {
    //     return Attribute::make(
    //         get: function () {

    //             // 1) Si tiene valor (guardado en DB)
    //             $value = $this->profile_photo;

    //             if (!empty($value)) {
    //                 // Si ya es URL externa
    //                 if (Str::startsWith($value, ['http://', 'https://'])) {
    //                     return $value;
    //                 }

    //                 // Si es ruta relativa (ej: "instructors/xxx.png")
    //                 return Storage::url($value);
    //             }

    //             // 2) Si NO tiene foto -> default por role
    //             return match ($this->role) {
    //                 'instructor' => Storage::url('instructors/instructor-ipf.png'),
    //                 'admin'      => Storage::url('admin/admin-ipf.png'),
    //                 default      => null, // student u otros: sin imagen (puedes devolver una genérica si quieres)
    //             };
    //         }
    //     );
    // }

    protected function profilePhoto(): Attribute {
        return Attribute::make(
            get: function (?string $value, array $attributes) {
                // 1. Si el usuario TIENE una foto subida en la base de datos
                if (!empty($value)) {
                    if (Str::startsWith($value, ['http://', 'https://'])) {
                        return $value;
                    }
                    return Storage::url($value);
                }

                // 2. Si NO tiene foto, revisamos el rol
                // OJO: Cambia '$attributes['role']' por la forma en que guardes el rol.
                // Si usas Spatie Permission, podrías cambiar esto a $this->roles->first()->name u otra lógica.
                $role = $attributes['role'] ?? null; 

                return match ($role) {
                    'instructor' => Storage::url('instructors/instructor-ipf.png'),
                    'admin'      => Storage::url('admin/admin-ipf.png'),
                    default      => null, // Los "student" (u otros) sin foto retornarán null
                };
            }
        );
    }
}
