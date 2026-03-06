<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanType extends Model
{
    use HasFactory;
    protected $table    = 'plan_type';
    protected $fillable = ['name'];
    public $timestamps  = false;

    public function courses(): HasMany {
        return $this->hasMany(Course::class, 'plan_type', 'id');
    }
}
