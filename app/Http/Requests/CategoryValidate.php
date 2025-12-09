<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryValidate extends FormRequest
{

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name'          => 'required|unique:categories,name,'.$this->id,
            'description'   => 'required|string',
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'El Nombre es requerido',
            'name.unique'   => 'El Nombre ya existe',
            'description.required' => 'La Descripción es requerida',
        ];
    }
}
