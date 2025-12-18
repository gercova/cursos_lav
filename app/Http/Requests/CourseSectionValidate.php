<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseSectionValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'order'         => 'required|integer|min:1',
            'is_active'     => 'boolean',
        ];
    }

    public function messages(): array {
        return [
            'title.required'    => 'El título es requerido',
            'title.max'         => 'El título debe tener una longitud máxima de 255 caracteres'
        ];
    }

    protected function prepareForValidation(){
        // Convertir checkboxes a booleanos
        $this->merge([
            'is_active' => $this->has('is_active') ? filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN) : false,
        ]);
    }
}
