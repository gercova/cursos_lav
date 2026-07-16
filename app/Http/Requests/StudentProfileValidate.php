<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentProfileValidate extends FormRequest {

    public function authorize(): bool {
        return auth()->check();
    }

    public function rules(): array {
        $userId = auth()->id();

        return [
            'dni'           => 'required|string|max:20|unique:users,dni,' . $userId,
            'names'         => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $userId,
            'country_code'  => 'required|string|max:5',
            'phone'         => 'required|string|max:20',
            'nationality'   => 'nullable|string|max:100',
            'address'       => 'nullable|string|max:500',
            'profession'    => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array {
        return [
            'dni.required'          => 'El DNI es requerido',
            'dni.max'               => 'El DNI tiene una longitud máxima de 20 dígitos',
            'dni.unique'            => 'El DNI ya existe en el sistema',
            'names.required'        => 'El nombre es requerido',
            'names.max'             => 'El nombre tiene una longitud máxima de 255 caracteres',
            'email.required'        => 'El email es requerido',
            'email.email'           => 'El formato ingresado no corresponde al de un correo',
            'email.unique'          => 'El email ya está registrado',
            'phone.required'        => 'El teléfono es requerido',
            'phone.max'             => 'El teléfono tiene una longitud máxima de 20 dígitos',
            'nationality.max'       => 'La nacionalidad tiene una longitud máxima de 100 caracteres',
            'address.max'           => 'La dirección tiene una longitud máxima de 500 caracteres',
            'profession.max'        => 'La profesión tiene una longitud máxima de 255 caracteres',
            'country_code.required' => 'El código de país es requerido',
            'country_code.max'      => 'El código de país tiene una longitud máxima de 5 caracteres',
            'profile_photo.image'   => 'El archivo debe ser una imagen válida.',
            'profile_photo.mimes'   => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'profile_photo.max'     => 'La imagen no debe pesar más de 2MB.',
        ];
    }
}
