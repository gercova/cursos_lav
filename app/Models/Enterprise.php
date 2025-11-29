<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    use HasFactory;

    protected $table        = 'enterprise';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'ruc',
        'company_name',
        'trade_name',
        'legal_representative_dni',
        'legal_representative',
        'address',
        'geographical_code',
        'city',
        'business_sector',
        'phrase',
        'description',
        'vision',
        'mission',
        'phone_number_1',
        'phonen_umber_2',
        'email',
        'facebook_link',
        'linkedin_link',
        'twitter_link',
        'instagram_link',
        'whatsapp_link'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
