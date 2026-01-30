<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'email'     => 'required|email',
            'password'  => 'required|min:6',
        ];
    }

    public function messages(): array {
        return [
            'email.required'    => 'El campo E-mail es requerido',
            'email.email'       => 'El campo E-mail debe ser un correo',
            'password.required' => 'El campo Contraseña es requerido',
            'password.min'      => 'El campo Contraseña debe tener más de 6 caracteres',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge([
            'email'     => trim(strip_tags($this->email)),
            'password'  => trim(strip_tags($this->password)),
        ]);
    }
}
