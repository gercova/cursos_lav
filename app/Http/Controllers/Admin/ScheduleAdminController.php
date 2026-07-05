<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanySchedule;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class ScheduleAdminController extends Controller
{
    /**
     * Lista el cronograma anual (grilla mes × curso).
     */
    public function index(Request $request): View
    {
        $year = (int) $request->get('year', now()->year);

        // Todos los códigos de empresa existentes (para el filtro)
        $companyCodes = User::whereNotNull('company_code')
            ->whereNotNull('company_code')
            ->where('company_code', '!=', '')
            ->distinct()
            ->pluck('company_code')
            ->sort()
            ->values();

        $filterCode = $request->get('company_code'); // null = ver todos (global)

        // Cronograma del año seleccionado
        $schedules = CompanySchedule::with('course')
            ->where('year', $year)
            ->when($filterCode !== null && $filterCode !== '', function ($q) use ($filterCode) {
                $q->where(function ($q2) use ($filterCode) {
                    $q2->whereNull('company_code')
                       ->orWhere('company_code', $filterCode);
                });
            })
            ->orderBy('month')
            ->get();

        // Agrupar por mes para la vista de grilla
        $byMonth = $schedules->groupBy('month');

        // Todos los cursos activos (para el selector)
        $courses = Course::with('category:id,name')
            ->where('is_active', true)
            ->orderBy('title')
            ->get(['id', 'title', 'category_id', 'is_training']);

        // Categorías con cursos activos (para filtro del selector)
        $categories = Category::whereHas('courses', function ($q) {
            $q->where('category_id', '!=', 4); // categoria es para paquetes de cursos
            $q->where('is_active', true);
        })->orderBy('name')->get(['id', 'name']);

        $months = CompanySchedule::$months;

        return view('admin.schedules.index', compact(
            'year',
            'byMonth',
            'courses',
            'categories',
            'months',
            'companyCodes',
            'filterCode',
        ));
    }

    /**
     * Guarda un nuevo item en el cronograma.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'month'            => 'required|integer|min:1|max:12',
            'year'             => 'required|integer|min:2024|max:2100',
            'company_code'     => 'nullable|string|max:50',
            'modality'         => 'nullable|string|max:50',
            'responsible_area' => 'nullable|string|max:100',
            'scope'            => 'nullable|string|max:100',
            'notes'            => 'nullable|string',
        ]);

        // Normalizar company_code vacío a null (significa TODOS)
        if (empty($data['company_code'])) {
            $data['company_code'] = null;
        }

        // Verificar duplicado
        $exists = CompanySchedule::where('course_id', $data['course_id'])
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->where('company_code', $data['company_code'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Este curso ya está programado para ese mes/año.',
            ], 422);
        }

        $schedule = CompanySchedule::create($data);
        $schedule->load('course');

        return response()->json([
            'success'  => true,
            'message'  => 'Curso agregado al cronograma correctamente.',
            'schedule' => [
                'id'               => $schedule->id,
                'month'            => $schedule->month,
                'year'             => $schedule->year,
                'course_id'        => $schedule->course_id,
                'course_title'     => $schedule->course->title,
                'company_code'     => $schedule->company_code,
                'modality'         => $schedule->modality,
                'responsible_area' => $schedule->responsible_area,
                'scope'            => $schedule->scope,
                'is_released'      => $schedule->is_released,
            ],
        ]);
    }

    /**
     * Actualiza un item del cronograma.
     */
    public function update(Request $request, CompanySchedule $schedule): JsonResponse
    {
        $data = $request->validate([
            'month'            => 'sometimes|integer|min:1|max:12',
            'year'             => 'sometimes|integer|min:2024|max:2100',
            'company_code'     => 'nullable|string|max:50',
            'modality'         => 'nullable|string|max:50',
            'responsible_area' => 'nullable|string|max:100',
            'scope'            => 'nullable|string|max:100',
            'notes'            => 'nullable|string',
            'is_active'        => 'sometimes|boolean',
        ]);

        if (array_key_exists('company_code', $data) && empty($data['company_code'])) {
            $data['company_code'] = null;
        }

        $schedule->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cronograma actualizado.',
        ]);
    }

    /**
     * Elimina un item del cronograma.
     */
    public function destroy(CompanySchedule $schedule): JsonResponse
    {
        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item eliminado del cronograma.',
        ]);
    }

    /**
     * API: devuelve todos los items del cronograma de un año (para AJAX).
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $year        = (int) $request->get('year', now()->year);
        $companyCode = $request->get('company_code');

        $schedules = CompanySchedule::with('course:id,title,image_url')
            ->where('year', $year)
            ->when($companyCode, function ($q) use ($companyCode) {
                $q->where(function ($q2) use ($companyCode) {
                    $q2->whereNull('company_code')
                       ->orWhere('company_code', $companyCode);
                });
            })
            ->orderBy('month')
            ->get()
            ->map(fn ($s) => [
                'id'               => $s->id,
                'month'            => $s->month,
                'month_name'       => $s->month_name,
                'year'             => $s->year,
                'course_id'        => $s->course_id,
                'course_title'     => $s->course?->title,
                'company_code'     => $s->company_code,
                'modality'         => $s->modality,
                'responsible_area' => $s->responsible_area,
                'scope'            => $s->scope,
                'is_active'        => $s->is_active,
                'is_released'      => $s->is_released,
            ]);

        return response()->json(['data' => $schedules]);
    }

    /**
     * Copia todo el cronograma de un año al año siguiente.
     */
    public function copyYear(Request $request): JsonResponse
    {
        $fromYear    = (int) $request->validate(['from_year' => 'required|integer'])['from_year'];
        $toYear      = $fromYear + 1;
        $companyCode = $request->get('company_code');

        $source = CompanySchedule::where('year', $fromYear)
            ->when($companyCode, fn ($q) => $q->where('company_code', $companyCode))
            ->get();

        $created = 0;
        foreach ($source as $item) {
            $exists = CompanySchedule::where('course_id', $item->course_id)
                ->where('month', $item->month)
                ->where('year', $toYear)
                ->where('company_code', $item->company_code)
                ->exists();

            if (! $exists) {
                CompanySchedule::create([
                    'course_id'        => $item->course_id,
                    'month'            => $item->month,
                    'year'             => $toYear,
                    'company_code'     => $item->company_code,
                    'modality'         => $item->modality,
                    'responsible_area' => $item->responsible_area,
                    'scope'            => $item->scope,
                    'notes'            => $item->notes,
                    'is_active'        => true,
                ]);
                $created++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Se copiaron {$created} items al cronograma de {$toYear}.",
            'to_year' => $toYear,
        ]);
    }
}
