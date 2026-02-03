<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Enterprise extends Model {

    use HasFactory;

    protected $table        = 'enterprise';
    protected $primaryKey   = 'id';
    protected $fillable = [
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
        'email',
        'facebook_link',
        'linkedin_link',
        'twitter_link',
        'instagram_link',
        'whatsapp_link',
        'logo_path',
        'favicon_path',
        'logo_principal',
        'logo_mini',
        'logotipo',
        'isologo',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessors para cada campo de imagen
    protected function logoPath(): Attribute {
        return $this->imageAttribute($this->attributes['logo_path'] ?? null);
    }

    protected function faviconPath(): Attribute {
        return $this->imageAttribute($this->attributes['favicon_path'] ?? null);
    }

    protected function logoPrincipal(): Attribute {
        return $this->imageAttribute($this->attributes['logo_principal'] ?? null);
    }

    protected function logoMini(): Attribute {
        return $this->imageAttribute($this->attributes['logo_mini'] ?? null);
    }

    protected function logotipo(): Attribute {
        return $this->imageAttribute($this->attributes['logotipo'] ?? null);
    }

    protected function isologo(): Attribute {
        return $this->imageAttribute($this->attributes['isologo'] ?? null);
    }

    // Método reutilizable para la lógica de imágenes
    private function imageAttribute(?string $value): Attribute {
        return Attribute::make(
            get: fn () => match (true) {
                empty($value) => asset('storage/photos/ipf-logo.png'),
                Str::startsWith($value, ['http://', 'https://']) => $value,
                default => Storage::url($value),
            }
        );
    }
}
