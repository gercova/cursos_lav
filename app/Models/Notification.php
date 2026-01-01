<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'link',
        'icon',
        'color',
        'read_at',
        'is_active',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Relación con usuario
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Scope para notificaciones no leídas
    public function scopeUnread($query) {
        return $query->whereNull('read_at');
    }

    // Scope para notificaciones activas
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    // Marcar como leída
    public function markAsRead() {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    // Marcar como no leída
    public function markAsUnread() {
        $this->update(['read_at' => null]);
    }
}
