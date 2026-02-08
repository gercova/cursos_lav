<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory;

    protected $table        = 'lessons';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'course_id',
        'course_section_id',
        'title',
        'description',
        'video_url',
        'duration',
        'order',
        'is_free',
        'is_active',
    ];

    protected $casts = [
        'is_free'   => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getTitleVimeoAttribute()
    {
        $slugs=[
            $this->section->course->slug,
            Str::slug($this->section->title),
            Str::slug($this->title),
            date('Y-m-d-H-i-s')
        ];

        return implode('-',$slugs);
    }

    public function section(): BelongsTo {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function course(): HasOneThrough {
        return $this->hasOneThrough(Course::class, CourseSection::class, 'id', 'id', 'course_section_id', 'course_id');
    }
}
