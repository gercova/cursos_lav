<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailUserCode extends Model
{
    use HasFactory;
    protected $table        = 'detail_user_code';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'user_id',
        'course_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
