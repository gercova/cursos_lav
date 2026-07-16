<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow {

    protected int   $availableSlots;
    protected int   $registered     = 0;
    protected int   $updated        = 0;
    protected int   $skippedInvalid = 0; // filas con datos incorrectos
    protected int   $skippedLimit   = 0; // filas rechazadas por límite de plan
    protected array $warnings       = [];

    public function __construct(int $availableSlots) {
        $this->availableSlots = $availableSlots;
    }

    public function collection(Collection $rows) {
        $authUser    = Auth::user();
        $parentId    = $authUser->id;
        $companyCode = $authUser->company_code ?? '';

        // Contador decremental de slots disponibles para NUEVOS usuarios
        $remainingSlots = $this->availableSlots;

        foreach ($rows as $index => $row) {

            $rowNumber = $index + 2; // fila 1 = cabecera

            // ── 1. Campos obligatorios ───────────────────────────────────────
            if (empty($row['dni']) || empty($row['nombres']) || empty($row['correo'])) {
                $this->warnings[]      = "Fila {$rowNumber}: Omitida — campos obligatorios incompletos (DNI, NOMBRES o CORREO).";
                $this->skippedInvalid++;
                continue;
            }

            $dni   = str_pad(trim((string) $row['dni']), 8, '0', STR_PAD_LEFT);
            $email = strtolower(trim((string) $row['correo']));

            // ── 2. Formato de email ──────────────────────────────────────────
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->warnings[]      = "Fila {$rowNumber}: El correo «{$email}» no tiene formato válido. Omitida.";
                $this->skippedInvalid++;
                continue;
            }

            // ── 3. DNI duplicado → solo actualizar parent_id y company_code si es un estudiante permitido ──
            $existingByDni = User::where('dni', $dni)->first();

            if ($existingByDni) {
                if ($existingByDni->role !== 'student') {
                    $this->warnings[] = "Fila {$rowNumber}: El DNI «{$dni}» pertenece a un usuario con rol administrativo o instructor. Omitida.";
                    $this->skippedInvalid++;
                    continue;
                }

                if ($existingByDni->parent_id !== null && $existingByDni->parent_id !== $parentId) {
                    $this->warnings[] = "Fila {$rowNumber}: El DNI «{$dni}» ya pertenece a colaboradores de otra empresa. Omitida.";
                    $this->skippedInvalid++;
                    continue;
                }

                $existingByDni->update([
                    'parent_id'    => $parentId,
                    'company_code' => $companyCode,
                    'expires_at'   => now()->addYear(),
                ]);
                $this->updated++;
                continue; // no consume slot
            }

            // ── 4. Email duplicado (con DNI distinto) → omitir ───────────────
            $existingByEmail = User::where('email', $email)->first();

            if ($existingByEmail) {
                $this->warnings[] = "Fila {$rowNumber}: El correo «{$email}» ya está registrado "."(DNI asociado: {$existingByEmail->dni}). Omitida.";
                $this->skippedInvalid++;
                continue; // no consume slot
            }

            // ── 5. Control de límite de asientos ────────────────────────────
            //      Solo los usuarios NUEVOS (que pasaron los checks anteriores)
            //      consumen un slot. Si no quedan, se rechaza la fila.
            if ($remainingSlots <= 0) {
                $this->skippedLimit++;
                continue;
            }

            // ── 6. Crear nuevo usuario ───────────────────────────────────────
            $countryCode = trim($row['codigo_pais'] ?? '') ?: '+51';

            User::create([
                'parent_id'     => $parentId,
                'dni'           => $dni,
                'names'         => trim($row['nombres'] ?? ''),
                'email'         => $email,
                'country_code'  => $countryCode,
                'phone'         => trim($row['telefono'] ?? ''),
                'nationality'   => $this->resolveNationality($countryCode),
                'address'       => trim($row['direccion'] ?? ''),
                'profession'    => trim($row['cargo_profesion'] ?? ''),
                'password'      => Hash::make('P4$$w0rd#.'),
                'company_code'  => $companyCode,
                'role'          => 'student',
                'expires_at'    => now()->addYear(), 
            ]);

            $remainingSlots--;
            $this->registered++;
        }

        // ── 7. Advertencia de límite alcanzado (solo si hubo rechazos por eso)
        if ($this->skippedLimit > 0) {
            $this->warnings[] = "Límite del plan alcanzado ({$this->availableSlots} asiento(s) disponibles). "
                . "{$this->skippedLimit} usuario(s) no pudieron registrarse por falta de espacio. "
                . "Actualice su plan para agregar más.";
        }
    }

    // ── Getters ───────────────────────────────────────────────────────────────

    public function getRegistered(): int  { return $this->registered; }
    public function getUpdated(): int     { return $this->updated; }
    public function getSkipped(): int     { return $this->skippedInvalid + $this->skippedLimit; }
    public function getSkippedLimit(): int{ return $this->skippedLimit; }
    public function getWarnings(): array  { return $this->warnings; }
    public function hasWarnings(): bool   { return !empty($this->warnings); }

    // ── Helper ────────────────────────────────────────────────────────────────

    protected function resolveNationality(?string $code): string {
        return match (trim((string) $code)) {
            '+51'  => 'Peruana',
            '+52'  => 'Mexicana',
            '+54'  => 'Argentina',
            '+56'  => 'Chilena',
            '+34'  => 'Española',
            '+1'   => 'Americana',
            '+591' => 'Boliviana',
            '+593' => 'Ecuatoriana',
            '+598' => 'Uruguaya',
            default => 'Otra',
        };
    }
}
