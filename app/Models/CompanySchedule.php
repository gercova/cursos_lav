<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySchedule extends Model
{
    use HasFactory;

    protected $table = 'company_schedules';

    protected $fillable = [
        'course_id',
        'month',
        'year',
        'company_code',
        'modality',
        'responsible_area',
        'scope',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'month'     => 'integer',
        'year'      => 'integer',
        'is_active' => 'boolean',
    ];

    // Nombres de los meses en español
    public static array $months = [
        1  => 'Enero',
        2  => 'Febrero',
        3  => 'Marzo',
        4  => 'Abril',
        5  => 'Mayo',
        6  => 'Junio',
        7  => 'Julio',
        8  => 'Agosto',
        9  => 'Setiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ];

    // ── Relaciones ──────────────────────────────────────────────────────────

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // ── Accessors ───────────────────────────────────────────────────────────

    public function getMonthNameAttribute(): string
    {
        return self::$months[$this->month] ?? '—';
    }

    /**
     * Devuelve true si el mes/año del cronograma ya llegó o pasó.
     */
    public function getIsReleasedAttribute(): bool
    {
        $now = now();
        if ($this->year < $now->year) {
            return true;
        }
        if ($this->year === $now->year && $this->month <= $now->month) {
            return true;
        }
        return false;
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    /**
     * Devuelve los cronogramas visibles para un company_code dado.
     * Incluye los globales (company_code = null).
     */
    public function scopeForCompany($query, string $companyCode)
    {
        return $query->where(function ($q) use ($companyCode) {
            $q->whereNull('company_code')
              ->orWhere('company_code', $companyCode);
        });
    }

    /**
     * Solo cronogramas cuyo mes/año ya llegó (cursos "activados").
     */
    public function scopeReleased($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->where('year', '<', $now->year)
              ->orWhere(function ($q2) use ($now) {
                  $q2->where('year', $now->year)
                     ->where('month', '<=', $now->month);
              });
        });
    }

    /**
     * Solo cronogramas futuros (cursos aún no liberados).
     */
    public function scopeUpcoming($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->where('year', '>', $now->year)
              ->orWhere(function ($q2) use ($now) {
                  $q2->where('year', $now->year)
                     ->where('month', '>', $now->month);
              });
        });
    }
}
