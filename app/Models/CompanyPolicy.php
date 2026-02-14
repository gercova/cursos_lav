<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyPolicy extends Model
{
    use HasFactory;

    protected $table        = 'business_policies';
    protected $primaryKey   = 'id';
    protected $fillable     = ['user_id', 'quantity'];

    protected $dates        = ['created_at', 'updated_at'];
    
    protected $casts        = [
        'created_at' => 'datetime', 
        'updated_at' => 'datetime',
    ];

    public function users(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
