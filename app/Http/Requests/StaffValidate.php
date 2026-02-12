<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'dni'           => 'required|string|max:20|unique:users',
            'names'         => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'country_code'  => 'required|string|max:5',
            'phone'         => 'required|string|max:20',
            'nationality'   => 'required|string|max:100',
            'address'       => 'required|string|max:500',
            'profession'    => 'required|string|max:255',
        ];
    }

    public function messages(): array {
        return [
            'dni.required'          => 'El DNI es obligatorio',
            'dni.max'               => 'El DNI tiene una longitud máxima de 20 dígitos',
            'dni.unique'            => 'El DNI ya existe',
            'names.required'        => 'Los Nombres son obligatorios',
            'names.max'             => 'Los Nombres tienen una longitud máxima de 255 caracteres',
            'email.required'        => 'El email es requerido',
            'email.email'           => 'El formato ingresado no corresponde al de un correo',
            'email.unique'          => 'El email ya está registrado',
            'phone.required'        => 'El teléfono es requerido',
            'phone.max'             => 'El teléfono tiene una longitud máxima de 20 dígitos',
            'nationality.required'  => 'La nacionalidad es olbigatoria',
            'nationality.max'       => 'La nacionalidad tiene una longitud máxima de 100 caracteres',
            'address.max'           => 'La dirección tiene una longitud máxima de 500 caracteres',
            'profession.max'        => 'La profesión tiene una longitud máxima de 500 caracteres',
            'password.required'     => 'La contraseña es requerida',
            'password.min'          => 'La contraseña tiene una longitud mínima de 8 caracteres',
            'password.confirmed'    => 'Debe confirmar la contraseña',
            'country_code.required' => 'El código de país es obligatorio',
        ];
    }
}
