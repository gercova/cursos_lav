<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'video_url'         => 'required|url|max:500',
            'duration'          => 'required|integer|min:1',
            'order'             => 'required|integer|min:1',
            'is_free'           => 'boolean',
            'is_active'         => 'boolean'
        ];
    }

    public function messages(): array {
        return [
            'title.required'                => 'El título es requerido',
            'title.max'                     => 'El título deber tener una longitud máxima de 255 caracteres',
            'description.required'          => 'La descripción es requerida',
            'video_url.required'            => 'El video es requerido',
            'video.url'                     => 'El video debe ser de tipo enlace ',
            'video.max'                     => 'El video debe tener una longitud máxima de 255 caracteres',
            'duration.required'             => 'La duración del video es requerida',
            'duration.integer'              => 'La duración del video debe ser de tipo entero',
            'duration.min'                  => 'La duración del video mínima es 1',
            'order.required'                => 'El orden es requerido',
            'order.integer'                 => 'El orden debe ser de tipo entero',
            'order.min'                     => 'El orden mínimo es 1',
        ];
    }

    protected function prepareForValidation(){
        // Convertir checkboxes a booleanos
        $this->merge([
            'is_free'   => $this->has('is_free') ? filter_var($this->is_free, FILTER_VALIDATE_BOOLEAN) : false,
            'is_active' => $this->has('is_active') ? filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN) : false,
        ]);
    }
}
