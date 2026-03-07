<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCoursePackage extends Model {
    use HasFactory;

    protected $table        = 'user_course_package';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'package_id',
        'course_id',
        'user_id'
    ];

    public function package(): BelongsTo {
        return $this->belongsTo(Course::class, 'package_id', 'id')->where('type', 'package');
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id')->where('type', 'course');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
