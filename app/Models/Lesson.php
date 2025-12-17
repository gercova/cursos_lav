<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Storage;

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

    public function section(): BelongsTo {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function course(): HasOneThrough {
        return $this->hasOneThrough(Course::class, CourseSection::class, 'id', 'id', 'course_section_id', 'course_id');
    }

    /*public function getVideoUrlAttribute(): ?string {
        $originalUrl = $this->getRawOriginal('video_url') ?? $this->getAttribute('video_url');

        if (!$originalUrl) {
            return null;
        }

        return Storage::url($originalUrl);
    }*/

    /*public function getMediaTypeAttribute(): string {
        $originalUrl = $this->getRawOriginal('video_url') ?? $this->getAttribute('video_url');

        if (!$originalUrl) {
            return 'none';
        }

        $extension = strtolower(pathinfo($originalUrl, PATHINFO_EXTENSION));

        $videoExtensions    = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'];
        $imageExtensions    = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $documentExtensions = ['pdf', 'doc', 'docx', 'txt', 'ppt', 'pptx'];

        if (in_array($extension, $videoExtensions)) {
            return 'video';
        } elseif (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $documentExtensions)) {
            return 'document';
        }

        return 'other';
    }*/
}
