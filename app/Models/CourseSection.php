<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class CourseSection extends Model
{
    use HasFactory;

    protected $table        = 'course_sections';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'course_id',
        'title',
        'description',
        'mediafile',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function getMediaUrlAttribute(): ?string {
        if (!$this->mediafile) {
            return null;
        }

        return Storage::url($this->mediafile);
    }

    /**
     * Get the media file type
     */
    public function getMediaTypeAttribute(): string {
        if (!$this->mediafile) {
            return 'none';
        }

        $extension = strtolower(pathinfo($this->mediafile, PATHINFO_EXTENSION));

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
    }
}
