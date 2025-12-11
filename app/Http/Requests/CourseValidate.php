<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title'         => 'required|string|max:255|unique:courses,title,'.$this->id,
            'slug'          => 'nullable|string|max:255|unique:courses,slug,'.$this->id,
            'description'   => 'required|string',
            'price'         => 'required|numeric|min:0',
            'promotion_price' => 'nullable|numeric|min:0|lt:price',
            'category_id'   => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:users,id',
            'level'         => 'required|in:beginner,intermediate,advanced,all',
            'duration'      => 'required|numeric|min:0',
            'is_active'     => 'boolean',
            'requirements'  => 'nullable|array',
            'requirements.*' => 'string|max:255',
            'what_you_learn' => 'required|array',
            'what_you_learn.*' => 'string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];
    }

    public function messages(): array {
        return [
            'title.max'                 => 'El título no puede tener más de 255 caracteres.',
            'title.unique'              => 'El título ya existe.',
            'slug.max'                  => 'El slug no puede tener más de 255 caracteres.',
            'slug.unique'               => 'El slug ya existe.',
            'description.required'      => 'La descripción es requerida.',
            'price.required'            => 'El precio es requerido.',
            'price.numeric'             => 'El precio debe ser numérico.',
            'promotion_price.numeric'   => 'El precio de promoción debe ser numérico.',
            'promotion_price.min'       => 'El precio de promoción mínimo debe ser 0.',
            'promotion_price.lt'        => 'El precio de promoción debe de ser menor que el precio real.',
            'category_id.required'      => 'La categoría es requerida.',
            'category_id.exists'        => 'La categoría no existe.',
            'instructor_id.required'    => 'El instructor es requerido.',
            'instructor_id.exists'      => 'El instructor no existe.',
            'level.required'            => 'El nivel es requerido.',
            'level.in'                  => 'El nivel no es válido.',
            'duration.required'         => 'La duración es requerida.',
            'duration.numeric'          => 'La duración debe ser numérica.',
            'duration.min'              => 'La duración mínima debe ser 0.',
            'requirements'              => 'nullable|array',
            'requirements.*.required'   => 'Cada requisito requiere una descripción.',
            'requirements.*.string'     => 'Cada requisito debe ser una cadena de texto.',
            'requirements.*.max'        => 'Cada requisito no puede tener más de 255 caracteres.',
            'what_you_learn.required'   => 'Debes proporcionar al menos un ítem a aprender.',
            'what_you_learn.*.string'   => 'Cada ítem a aprender debe ser una cadena de texto.',
            'what_you_learn.*.max'      => 'Cada ítem a aprender no puede tener más de 255 caracteres.',
            'image.image'               => 'El archivo ingresado debe ser una imagen válida.',
            'image.max'                 => 'El archivo no puede pesar más de 5MB.',

        ];
    }
}
