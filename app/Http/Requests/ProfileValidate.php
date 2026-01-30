<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'names'         => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $this->id,
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
            'profession'    => 'required|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'names.required'    => 'El nombre es requerido',
            'names.max:255'     => 'El nombre tiene una longitud máxima de 255 caracteres',
            'email.required'    => 'El e-mail es requerido',
            'email.email'       => 'El e-mail no tiene el formato requerido',
            'email.unique'      => 'El e-mail ya existe',
            'phone.required'    => 'El teléfono es requerido',
            'phone.max'         => 'El teléfono tiene una longitud máxima de 20 dígitos',
            'address.required'  => 'La dirección es requerida',
            'address.max'       => 'La dirección tiene una longitud máxima de 500 caracteres',
            'profession.required' => 'La profesión es requerida',
            'profession.max'    => 'La profesión tiene una longitud máxima de 255 caracteres',
        ];
    }
}
