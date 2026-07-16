<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
                'confirmed'
            ],
        ];
    }

    public function messages(): array {
        return [
            'password.required'     => 'La contraseña es requerida',
            'password.confirmed'    => 'Debe confirmar la contraseña',
        ];
    }
}
