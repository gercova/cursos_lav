<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array {
        return [
            'password.required'     => 'La contraseña es requerida',
            'password.min'          => 'La contraseña tiene una longitud mínima de 8 caracteres',
            'password.confirmed'    => 'Debe confirmar la contraseña',
        ];
    }
}
