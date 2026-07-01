<?php

namespace Database\Seeders;

use App\Models\CompanySchedule;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CompanyScheduleSeeder extends Seeder
{
    /**
     * Precarga el cronograma anual de capacitaciones 2026.
     * Distribuye cursos reales por mes con su modalidad y área responsable.
     * company_code = null significa que aplica a TODAS las empresas (global).
     */
    public function run(): void
    {
        $year = (int) date('Y'); // Año actual

        // Obtener IDs de cursos reales que existen en la BD
        // Tomamos los primeros 24 cursos activos con categoría (no paquetes)
        $courses = Course::where('is_active', true)
            ->whereNotNull('category_id')
            ->whereIn('category_id', [1, 2, 3, 5, 11]) // SST, Medio Ambiente, Forestales, SSOMA, Primeros Auxilios
            ->orderBy('id')
            ->limit(24)
            ->pluck('id')
            ->toArray();

        if (empty($courses)) {
            $this->command->warn('No se encontraron cursos activos para programar. Omitiendo seeder.');
            return;
        }

        // Distribuir cursos en 12 meses (2 cursos por mes aprox.)
        $schedule = [
            // Mes => [ [course_index, modalidad, area_responsable, alcance], ... ]
            1  => [
                [0,  'Virtual',    'SST',        'Todos los trabajadores'],
                [1,  'Virtual',    'RRHH',       'Personal nuevo'],
            ],
            2  => [
                [2,  'Virtual',    'SST',        'Todos los trabajadores'],
                [3,  'Presencial', 'Enfermería', 'Brigadistas'],
            ],
            3  => [
                [4,  'Virtual',    'SST',        'Supervisores'],
                [5,  'Mixto',     'Medio Ambiente', 'Todos los trabajadores'],
            ],
            4  => [
                [6,  'Virtual',    'SST',        'Todos los trabajadores'],
                [7,  'Virtual',    'RRHH',       'Conductores'],
            ],
            5  => [
                [8,  'Virtual',    'SST',        'Todos los trabajadores'],
                [9,  'Presencial', 'Enfermería', 'Personal de campo'],
            ],
            6  => [
                [10, 'Virtual',    'SST',        'Todos los trabajadores'],
                [11, 'Mixto',     'Medio Ambiente', 'Supervisores'],
            ],
            7  => [
                [12, 'Virtual',    'SST',        'Todos los trabajadores'],
                [13, 'Virtual',    'RRHH',       'Personal nuevo'],
            ],
            8  => [
                [14, 'Virtual',    'SST',        'Todos los trabajadores'],
                [15, 'Presencial', 'Enfermería', 'Brigadistas'],
            ],
            9  => [
                [16, 'Virtual',    'SST',        'Supervisores'],
                [17, 'Mixto',     'Medio Ambiente', 'Todos los trabajadores'],
            ],
            10 => [
                [18, 'Virtual',    'SST',        'Todos los trabajadores'],
                [19, 'Virtual',    'RRHH',       'Conductores'],
            ],
            11 => [
                [20, 'Virtual',    'SST',        'Todos los trabajadores'],
                [21, 'Presencial', 'Enfermería', 'Personal de campo'],
            ],
            12 => [
                [22, 'Virtual',    'SST',        'Todos los trabajadores'],
                [23, 'Mixto',     'Medio Ambiente', 'Supervisores'],
            ],
        ];

        $created = 0;

        foreach ($schedule as $month => $items) {
            foreach ($items as [$courseIndex, $modality, $area, $scope]) {
                // Si no hay suficientes cursos, ciclar
                $courseId = $courses[$courseIndex % count($courses)];

                // Verificar que no exista ya
                $exists = CompanySchedule::where('course_id', $courseId)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->whereNull('company_code')
                    ->exists();

                if (!$exists) {
                    CompanySchedule::create([
                        'course_id'        => $courseId,
                        'month'            => $month,
                        'year'             => $year,
                        'company_code'     => null, // Global para todas las empresas
                        'modality'         => $modality,
                        'responsible_area' => $area,
                        'scope'            => $scope,
                        'notes'            => null,
                        'is_active'        => true,
                    ]);
                    $created++;
                }
            }
        }

        $this->command->info("Se crearon {$created} registros en el cronograma de {$year}.");
    }
}
