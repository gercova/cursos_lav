<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Imports\UsersImport;
use App\Models\CompanyPolicy;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            ->first();

        $hasAnyPackage = User::find(auth()->id())
            ->studentCourses()
            ->where('courses.type', 'package')
            ->exists();
        
        // Obtener límite de usuarios
        $countUser      = User::where('company_code', $companyCode)->count();
        // $limitUser      = CompanyPolicy::where('user_id', Auth::id())->first();
        $limitUser = User::find(auth()->id())->studentCourses()->where('courses.type', 'package')->first(); // Obtener total de asientos que tiene un paquete comprado
        $availableSlots = ($limitUser->seats_max ?? 0) + 1 - $countUser;

        // return view('business.import', compact('availableSlots'));
        return view('student.company.import', compact('availableSlots', 'hasAnyPackage', 'enrolledPackage'));
    }

    public function import(Request $request){

        $excel = $request->file('file');
        if (empty($excel)) {
            return back()->withErrors($request->file('file'))->with('error', 'Seleccione un archivo.')->withInput();
        }

        $extension = $excel->extension();
        if ($extension != 'xlsx') {
            return back()->withErrors($request->file('file'))->with('error', 'Formato no válido.')->withInput();
        }

        try {
            Excel::import(new UserImport, $excel);
            return redirect()->route('company.list')->with('success', 'Se importaron los datos correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Algo salió mal: ' . $e->getMessage())->withInput();
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

        // $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet    = new Spreadsheet();
        $sheet          = $spreadsheet->getActiveSheet();

        // Establecer encabezados
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index); // A, B, C, etc.
            $sheet->setCellValue($column . '1', $header);
            
            // Estilo para encabezados
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getStyle($column . '1')->getFill()
                // ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->setFillType(Fill::FILL_SOLID)
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

        #$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer = new Xlsx($spreadsheet);
        
        if (!is_dir(public_path('templates'))) {
            mkdir(public_path('templates'), 0755, true);
        }
        
        $writer->save(public_path('templates/plantilla_importacion_usuarios.xlsx'));
    }
}