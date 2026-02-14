<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\CompanyPolicy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BusinessImportController extends Controller {
    
    public function __construct() {
        $this->middleware(['auth:sanctum', 'business', 'prevent.back']);
    }

    /**
     * Mostrar vista de importación
     */
    public function showImportForm() {
        $user           = Auth::user();
        $companyCode    = $user->company_code;
        
        // Obtener límite de usuarios
        $countUser      = User::where('company_code', $companyCode)->count();
        $limitUser      = CompanyPolicy::where('user_id', Auth::id())->first();
        $availableSlots = ($limitUser->quantity ?? 0) + 1 - $countUser;

        return view('business.import', compact('availableSlots'));
    }

    /**
     * Procesar la importación de usuarios
     */
    public function import(Request $request) {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // 5MB max
        ]);

        $user = Auth::user();
        $companyCode = $user->company_code;

        // Verificar límite antes de importar
        $countUser  = User::where('company_code', $companyCode)->count();
        $limitUser  = CompanyPolicy::where('user_id', Auth::id())->first();
        $maxUsers   = (int) ($limitUser->quantity ?? 0) + 1;

        if ($countUser >= $maxUsers) {
            return redirect()->back()->with('error', 'Has alcanzado el límite máximo de usuarios. Solicita un cambio de plan al administrador.');
        }

        DB::beginTransaction();
        try {
            $import = new UsersImport($companyCode, Auth::id());
            Excel::import($import, $request->file('file'));

            $successCount   = $import->getSuccessCount();
            $failedCount    = $import->getFailedCount();
            $failures       = $import->failures();
            $failedRows     = $import->getFailedRows();

            DB::commit();

            if ($successCount > 0) {
                $message = "Se importaron {$successCount} usuarios exitosamente.";
                if ($failedCount > 0) {
                    $message .= " {$failedCount} usuarios no pudieron ser importados.";
                }
                
                return redirect()->route('company.list')
                    ->with('success', $message)
                    ->with('import_failures', $failures)
                    ->with('failed_rows', $failedRows);
            } else {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'No se pudo importar ningún usuario. Verifica el archivo e intenta nuevamente.')
                    ->with('import_failures', $failures);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            return redirect()->back()
                ->with('error', 'Error de validación en el archivo Excel')
                ->with('import_failures', $failures);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Descargar plantilla de importación
     */
    public function downloadTemplate(): BinaryFileResponse
    {
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
    private function generateTemplateFile() {
        $headers = [
            'DNI',
            'NOMBRES',
            'CORREO',
            'CODIGO PAIS',
            'TELEFONO',
            'DIRECCIÓN',
            'CARGO / PROFESIÓN'
        ];

        $exampleData = [
            '12345678',
            'JUAN PEREZ LOPEZ',
            'juan.perez@ejemplo.com',
            '+51',
            '987654321',
            'Av. Principal 123',
            'DESARROLLADOR'
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer encabezados
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index); // A, B, C, etc.
            $sheet->setCellValue($column . '1', $header);
            
            // Estilo para encabezados
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getStyle($column . '1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4CAF50');
            $sheet->getStyle($column . '1')->getFont()->getColor()->setARGB('FFFFFFFF');
        }

        // Agregar datos de ejemplo
        foreach ($exampleData as $index => $value) {
            $column = chr(65 + $index);
            $sheet->setCellValue($column . '2', $value);
        }

        // Agregar instrucciones
        $sheet->setCellValue('A4', 'INSTRUCCIONES:');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->setCellValue('A5', '• El DNI y CORREO deben ser únicos en el sistema');
        $sheet->setCellValue('A6', '• CÓDIGO PAIS: Usar +51 (Perú), +54 (Argentina), +56 (Chile), +591 (Bolivia), +593 (Ecuador), +598 (Uruguay)');
        $sheet->setCellValue('A7', '• Si no especifica código de país, se asignará +51 por defecto');
        $sheet->setCellValue('A8', '• La contraseña inicial será: P4$$w0rd#.');
        $sheet->setCellValue('A9', '• Los campos marcados con * son obligatorios');

        // Autoajustar columnas
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        if (!is_dir(public_path('templates'))) {
            mkdir(public_path('templates'), 0755, true);
        }
        
        $writer->save(public_path('templates/plantilla_importacion_usuarios.xlsx'));
    }
}