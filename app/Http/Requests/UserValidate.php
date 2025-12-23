<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'dni'           => 'required|string|max:20|unique:users,dni',
            'names'         => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'country_code'  => 'required',
            'phone'         => 'required|string|max:20',
            'nationality'   => 'nullable|string|max:100',
            'address'       => 'nullable|string|max:500',
            'profession'    => 'nullable|string|max:255',
            'role'          => ['required', Rule::in(['student', 'instructor', 'admin'])],
        ];
    }

    public function messages(): array {
        return [
            'dni.required'      => 'El DNI es requerido',
            'dni.max'           => 'El DNI tiene una longitud máxima de 20 dígitos',
            'dni.unique'        => 'El DNI ya existe',
            'names.required'    => 'El nombre es requerido',
            'names.max'         => 'El nombre tiene una longitud máxima de 255 caracteres',
            'email.required'    => 'El email es requerido',
            'email.email'       => 'El formato ingresado no corresponde al de un correo',
            'email.unique'      => 'El email ya existe',
            'password.required' => 'La contraseña es requerida',
            'password.min'      => 'La contraseña tiene una longitud mínima de 8 caracteres',
            'password.confirmed' => 'Debe confirmar la contraseña',
            'country_code.required' => 'El código país es requerido',
            'phone.required'    => 'El teléfono es requerido',
            'phone.max'         => 'El teléfono tiene una longitud máxima de 20 dígitos',
            'nationality.max'   => 'La nacionalidad tiene una longitud máxima de 100 caracteres',
            'address.max'       => 'La dirección tiene una longitud máxima de 500 caracteres',
            'profession.max'    => 'La profesión tiene una longitud máxima de  500 caracteres',
            'role.required'     => 'El rol es requerido',
        ];
    }
}
