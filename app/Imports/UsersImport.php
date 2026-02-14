<?php

namespace App\Imports;

use App\Models\User;
use App\Models\CompanyPolicy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    protected $companyCode;
    protected $userId;
    protected $successCount = 0;
    protected $failedCount = 0;
    protected $failedRows = [];

    public function __construct($companyCode, $userId) {
        $this->companyCode = $companyCode;
        $this->userId = $userId;
    }

    /**
     * Mapeo de las filas del Excel al modelo User
     */
    public function model(array $row) {
        // Verificar límite de usuarios antes de importar
        $countUser = User::where('company_code', $this->companyCode)->count();
        $limitUser = CompanyPolicy::where('user_id', $this->userId)->first();

        if ($countUser >= (int) ($limitUser->quantity ?? 0) + 1) {
            $this->failedCount++;
            $this->failedRows[] = [
                'row'   => $row,
                'error' => 'Límite de usuarios alcanzado'
            ];
            return null;
        }

        // Determinar nacionalidad basada en el código de país
        $countryCode = $row['codigo_pais'] ?? '+51';
        $nationality = $this->getNationality($countryCode);

        // Generar código único de promoción
        $code = $this->generateUniqueCode();

        $this->successCount++;

        return new User([
            'dni'               => $row['dni'],
            'names'             => $row['nombres'],
            'email'             => $row['correo'],
            'password'          => Hash::make('P4$$w0rd#.'),
            'country_code'      => $countryCode,
            'phone'             => $row['telefono'] ?? null,
            'address'           => $row['direccion'] ?? null,
            'profession'        => $row['cargo_profesion'] ?? null,
            'nationality'       => $nationality,
            'role'              => 'student',
            'company_code'      => $this->companyCode,
            'is_active'         => true,
            'email_verified_at' => now(),
            'code'              => $code,
        ]);
    }

    /**
     * Reglas de validación
     */
    public function rules(): array {
        return [
            'dni'       => 'required|string|max:20|unique:users,dni',
            'nombres'   => 'required|string|max:255',
            'correo'    => 'required|email|max:255|unique:users,email',
            'telefono'  => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'cargo_profesion' => 'nullable|string|max:255',
        ];
    }

    /**
     * Mensajes personalizados de validación
     */
    public function customValidationMessages() {
        return [
            'dni.required'    => 'El DNI es obligatorio',
            'dni.unique'      => 'El DNI ya está registrado',
            'nombres.required' => 'Los nombres son obligatorios',
            'correo.required' => 'El correo es obligatorio',
            'correo.email'    => 'El correo no tiene un formato válido',
            'correo.unique'   => 'El correo ya está registrado',
        ];
    }

    /**
     * Obtener nacionalidad según código de país
     */
    private function getNationality($code) {
        $nationalities = [
            '+51'  => 'Peruano',
            '+54'  => 'Argentino',
            '+56'  => 'Chileno',
            '+591' => 'Boliviano',
            '+593' => 'Ecuatoriano',
            '+598' => 'Uruguayo',
        ];

        if (empty($code) || $code === '+51') {
            return 'Peruano';
        }

        return $nationalities[$code] ?? 'Peruano';
    }

    /**
     * Generar código único para el usuario
     */
    private function generateUniqueCode() {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('code', $code)->exists());

        return $code;
    }

    public function getSuccessCount() {
        return $this->successCount;
    }

    public function getFailedCount() {
        return $this->failedCount;
    }

    public function getFailedRows() {
        return $this->failedRows;
    }
}