<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {

    use HasApiTokens, HasFactory, Notifiable;

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

    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }

    public function courses() {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function certificates() {
        return $this->hasMany(Certificate::class);
    }

    public function examAttempts() {
        return $this->hasMany(ExamAttempt::class);
    }

    public function cartItems() {
        return $this->hasMany(Cart::class);
    }
}
