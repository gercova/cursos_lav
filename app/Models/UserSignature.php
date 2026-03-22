<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserSignature extends Model
{
    use HasFactory;

    protected $table        = 'user_signatures';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'user_id',
        'signature',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    protected function signature(): Attribute {
        return Attribute::make(
            get: function (?string $value, array $attributes) {
                // 1. Si el usuario TIENE una foto subida en la base de datos
                if (!empty($value)) {
                    if (Str::startsWith($value, ['http://', 'https://'])) {
                        return $value;
                    }

                    return Storage::url($value);
                }

                // 2. Si NO tiene foto, revisamos el rol
                // OJO: Cambia '$attributes['role']' por la forma en que guardes el rol.
                // Si usas Spatie Permission, podrías cambiar esto a $this->roles->first()->name u otra lógica.
                $role = $attributes['role'] ?? null; 

                return match ($role) {
                    'instructor' => Storage::url('instructors/instructor-ipf.png'),
                    'admin'      => Storage::url('admin/admin-ipf.png'),
                    default      => null, // Los "student" (u otros) sin foto retornarán null
                };
            }
        );
    }
}
