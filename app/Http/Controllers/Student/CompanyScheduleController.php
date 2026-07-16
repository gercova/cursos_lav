<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CompanySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyScheduleController extends Controller
{
    /**
     * Muestra el cronograma anual de capacitaciones para el usuario empresa
     * y sus usuarios asociados (misma company_code).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // El usuario debe tener company_code asignado
        $companyCode = $user->company_code
            ?? ($user->parent ? $user->parent->company_code : null);

        $year = (int) $request->get('year', now()->year);
        // Obtener los cronogramas visibles para este código de empresa
        // Solo se muestran cursos del mes actual y meses anteriores (no futuros)
        $schedules = CompanySchedule::with('course:id,title,image_url,slug')
            ->where('year', $year)
            ->where('is_active', true)
            ->released()   // ← solo mes actual y anteriores
            ->when($companyCode, function ($q) use ($companyCode) {
                // Incluye globales (null) + los específicos de su empresa
                $q->where(function ($q2) use ($companyCode) {
                    $q2->whereNull('company_code')
                       ->orWhere('company_code', $companyCode);
                });
            }, function ($q) {
                // Sin company_code, solo ver los globales
                $q->whereNull('company_code');
            })
            ->orderBy('month')
            ->get();

        // Agrupar por mes
        $byMonth = $schedules->groupBy('month');

        // Totales para las tarjetas resumen
        $totalItems    = $schedules->count();
        $releasedCount = $schedules->filter(fn ($s) => $s->is_released)->count();
        $upcomingCount = $totalItems - $releasedCount;

        return view('student.company.schedule', compact(
            'year',
            'byMonth',
            'totalItems',
            'releasedCount',
            'upcomingCount',
            'companyCode',
        ));
    }
}
