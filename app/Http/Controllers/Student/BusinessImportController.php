<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BusinessImportController extends Controller {
    
    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    /**
     * Mostrar vista de importación
     */
    public function showImportForm() {
        $user           = Auth::user();
        $companyCode    = $user->company_code;
        
        $enrolledPackage = User::find(auth()->id())
            ->studentCourses()
            ->where('courses.type', 'package')
            ->orderByDesc('courses.plan_type_id')
            ->first();

        $hasAnyPackage = User::find(auth()->id())
            ->studentCourses()
            ->where('courses.type', 'package')
            ->exists();
        
        // Obtener límite de usuarios
        $countUser = User::where('company_code', $companyCode)->count();
        
        // Obtener total de asientos que tiene un paquete comprado
        $availableSlots = ($enrolledPackage->seats_max ?? 0) + 1 - $countUser;

        $stats = [
            'total'         => User::where('users.parent_id', auth()->id())->where('users.id', '!=', Auth::id())->count(),
            'seats_max'     => $enrolledPackage->seats_max ?? 0,
            'available'     => $availableSlots,
            'limit'         => ($enrolledPackage->quantity ?? 0) + 1,
        ];

        return view('student.company.import', compact('availableSlots', 'hasAnyPackage', 'enrolledPackage'));
    }

    // public function import(Request $request) {
    //     $excel = $request->file('file');

    //     // ── 1. Validar que se haya enviado un archivo ────────────────────────
    //     if (empty($excel)) {
    //         return back()
    //             ->with('error', 'Seleccione un archivo.')
    //             ->withInput();
    //     }

    //     if ($excel->extension() !== 'xlsx') {
    //         return back()
    //             ->with('error', 'Formato no válido. Solo se aceptan archivos .xlsx.')
    //             ->withInput();
    //     }

    //     $authUser    = Auth::user();
    //     $companyCode = $authUser->company_code ?? '';

    //     // ── 2. Verificar que el usuario tenga un paquete activo ──────────────
    //     $package = User::find($authUser->id)
    //         ->studentCourses()
    //         ->where('courses.type', 'package')
    //         ->first();

    //     if (!$package) {
    //         return back()
    //             ->with('error', 'No cuenta con un paquete activo para importar usuarios.')
    //             ->withInput();
    //     }

    //     $seatsMax = $package->seats_max ?? 0;

    //     // ── 3. Pre-check: usuarios ya registrados bajo este administrador ────
    //     $existingUsersCount = User::where(function ($query) use ($authUser, $companyCode) {
    //             $query->where('parent_id', $authUser->id)->orWhere('company_code', $companyCode);
    //         })
    //         ->where('id', '!=', $authUser->id) // excluir al propio administrador
    //         ->count();

    //     // +1 porque el propio usuario administrador ocupa un asiento
    //     $availableSlots = $seatsMax + 1 - ($existingUsersCount + 1);

    //     // Normalizar: si ya se superó el límite, no quedan slots
    //     $availableSlots = max(0, $availableSlots);

    //     if ($availableSlots === 0) {
    //         return back()
    //             ->with('warning', "Ha alcanzado el límite máximo de {$seatsMax} usuario(s) permitido(s) en su plan. "
    //                 . "Para agregar más usuarios, actualice su plan.")
    //             ->withInput();
    //     }

    //     // ── 4. Importar con límite de asientos disponibles ───────────────────
    //     try {
    //         $import = new UserImport($availableSlots);
    //         Excel::import($import, $excel);

    //         $registered = $import->getRegistered();
    //         $updated    = $import->getUpdated();
    //         $skipped    = $import->getSkipped();
    //         $warnings   = $import->getWarnings();

    //         // ── 5. Construir mensaje de respuesta ────────────────────────────
    //         $summaryParts = [];

    //         if ($registered > 0) {
    //             $summaryParts[] = "{$registered} usuario(s) registrado(s) correctamente";
    //         }

    //         if ($updated > 0) {
    //             $summaryParts[] = "{$updated} usuario(s) actualizado(s) (ya existían en el sistema)";
    //         }

    //         if ($skipped > 0 && empty($warnings)) {
    //             $summaryParts[] = "{$skipped} fila(s) omitida(s) por datos incompletos";
    //         }

    //         $successMessage = implode(', ', $summaryParts) . '.';

    //         // Si hay advertencias (límite alcanzado u otros problemas), redirigir con ellas
    //         if (!empty($warnings)) {
    //             return redirect()
    //                 ->route('company.list')
    //                 ->with('success', $successMessage)
    //                 ->with('warnings', $warnings);
    //         }

    //         return redirect()
    //             ->route('company.list')
    //             ->with('success', $successMessage);

    //     } catch (ValidationException $e) {
    //         return back()->withErrors($e->errors())->withInput();
    //     } catch (\Exception $e) {
    //         return back()
    //             ->with('error', 'Ocurrió un error al procesar el archivo: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

    public function import(Request $request) {
        $excel = $request->file('file');

        // ── 1. Validar archivo ───────────────────────────────────────────────
        if (empty($excel)) {
            return back()->with('error', 'Seleccione un archivo.')->withInput();
        }

        if ($excel->extension() !== 'xlsx') {
            return back()->with('error', 'Formato no válido. Solo se aceptan archivos .xlsx.')->withInput();
        }

        $authUser = Auth::user();
        $companyCode = $authUser->company_code ?? '';

        // ── 2. Verificar paquete activo ──────────────────────────────────────
        $package = User::find($authUser->id)
            ->studentCourses()
            ->where('courses.type', 'package')
            ->orderByDesc('courses.plan_type_id')
            ->first();

        if (!$package) {
            return back()->with('error', 'No cuenta con un paquete activo para importar usuarios.')->withInput();
        }

        $seatsMax = $package->seats_max ?? 0;

        // ── 3. Pre-check: usuarios ya registrados bajo este administrador ────
        $existingUsersCount = User::where(function ($query) use ($authUser, $companyCode) {
                $query->where('parent_id', $authUser->id)->orWhere('company_code', $companyCode);
            })
            ->where('id', '!=', $authUser->id) // excluir al propio administrador
            ->count();

        // +1 porque el propio usuario administrador ocupa un asiento
        $availableSlots = $seatsMax + 1 - ($existingUsersCount + 1);

        // Normalizar: si ya se superó el límite, no quedan slots
        $availableSlots = max(0, $availableSlots);

        if ($availableSlots === 0) {
            return back()
                ->with('warning', "Ha alcanzado el límite máximo de {$seatsMax} usuario(s) permitido(s) en su plan. "
                    . "Para agregar más usuarios, actualice su plan.")
                ->withInput();
        }

        // ── 4. Importar pasando los slots reales disponibles ──────────────────
        try {
            $import = new UserImport($availableSlots);
            Excel::import($import, $excel);

            $registered = $import->getRegistered();
            $updated    = $import->getUpdated();
            $skipped    = $import->getSkipped();
            $warnings   = $import->getWarnings();

            // ── 5. Construir resumen ──────────────────────────────────────────
            $summaryParts = [];

            if ($registered > 0) {
                $summaryParts[] = "{$registered} usuario(s) registrado(s) correctamente";
            }

            if ($updated > 0) {
                $summaryParts[] = "{$updated} usuario(s) actualizado(s) (ya existían en el sistema)";
            }

            if ($skipped > 0 && empty($warnings)) {
                $summaryParts[] = "{$skipped} fila(s) omitida(s) por datos incompletos";
            }

            $successMessage = empty($summaryParts) ? 'No se realizaron cambios.' : implode(', ', $summaryParts) . '.';

            if (!empty($warnings)) {
                return redirect()->route('company.list')->with('success', $successMessage)->with('warnings', $warnings);
            }

            return redirect()->route('company.list')->with('success', $successMessage);

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al procesar el archivo: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Descargar plantilla de importación
     */
    public function downloadTemplate(): BinaryFileResponse {
        $filePath = public_path('templates/plantilla_importacion_usuarios.xlsx');
        
        // Si el archivo no existe, crearlo dinámicamente
        if (!file_exists($filePath)) {
            $this->generateTemplateFile();
        }

        return response()->download($filePath, 'plantilla_importacion_usuarios.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Generar archivo de plantilla
     */
    private function generateTemplateFile(): void {
        $headers = [
            'DNI', 'NOMBRES', 'CORREO', 'CODIGO PAIS', 'TELEFONO', 'DIRECCIÓN', 'CARGO / PROFESIÓN',
        ];

        $exampleData = [
            '12345678', 'JUAN PEREZ LOPEZ', 'juan.perez@ejemplo.com', '+51', '987654321', 'Av. Principal 123', 'DESARROLLADOR',
        ];

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        foreach ($headers as $index => $header) {
            $column = chr(65 + $index);
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getStyle($column . '1')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4CAF50');
            $sheet->getStyle($column . '1')->getFont()->getColor()->setARGB('FFFFFFFF');
        }

        foreach ($exampleData as $index => $value) {
            $column = chr(65 + $index);
            $sheet->setCellValue($column . '2', $value);
        }

        $sheet->setCellValue('A4', 'INSTRUCCIONES:');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->setCellValue('A5', '• El DNI y CORREO deben ser únicos en el sistema');
        $sheet->setCellValue('A6', '• CÓDIGO PAIS: +51 (Perú), +54 (Argentina), +56 (Chile), +591 (Bolivia), +593 (Ecuador), +598 (Uruguay)');
        $sheet->setCellValue('A7', '• Si no especifica código de país, se asignará +51 por defecto');
        $sheet->setCellValue('A8', '• La contraseña inicial será: P4$$w0rd#.');
        $sheet->setCellValue('A9', '• Los campos DNI, NOMBRES y CORREO son obligatorios');

        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        if (!is_dir(public_path('templates'))) {
            mkdir(public_path('templates'), 0755, true);
        }

        $writer->save(public_path('templates/plantilla_importacion_usuarios.xlsx'));
    }
}