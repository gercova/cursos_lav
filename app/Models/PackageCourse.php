<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageCourse extends Model
{
    use HasFactory;

    protected $table        = 'package_courses';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'package_id',
        'course_id',
        'quantity',
        'sort_order',
    ];

    public function package(): BelongsTo {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

}
