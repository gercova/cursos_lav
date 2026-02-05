<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\StudentActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isStudent() {
        return $this->role === 'student';
    }

    public function isInstructor() {
        return $this->role === 'instructor';
    }

    public function enrollments(): HasMany {
        return $this->hasMany(Enrollment::class, 'user_id', 'id');
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
}
