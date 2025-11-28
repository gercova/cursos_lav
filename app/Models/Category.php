<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $tables       = 'categories';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function courses(): HasMany {
        return $this->hasMany(Course::class);
    }

    public function getRouteKeyName() {
        return 'slug';
    }
}
