<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserExpirationValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'expires_at' => 'required|date',
        ];
    }

    public function messages(): array {
        return [
            'expires_at.required' => 'La fecha de expiración es requerida.',
            'expires_at.date'     => 'La fecha de expiración debe ser una fecha válida.',
        ];
    }
}
