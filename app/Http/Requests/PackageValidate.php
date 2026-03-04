<?php

namespace App\Http\Requests; // Asegúrate de que tu namespace sea correcto

use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class PackageValidate extends FormRequest {
    
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name'                                  => 'required|string|max:255',
            'description'                           => 'nullable|string',
            'which_includes'                        => 'required|array',
            'which_includes.*'                      => 'required|string',
            'price'                                 => 'required|numeric|min:0',
            'promotion_price'                       => 'nullable|numeric|min:0|lt:price',
            'course_limit'                          => 'nullable|integer',
            'seats_min'                             => 'required|integer|min:1',
            'seats_max'                             => 'required|integer|min:1',
            'is_active'                             => 'boolean',
            'meta_description'                      => 'nullable|string|max:200',
            'meta_keywords'                         => 'nullable|string|max:1000',
            // 'courses'                               => 'nullable|array',
            // 'courses.*.id'                          => 'exists:courses,id',
            // 'courses.*.sessions_per_course'         => 'nullable|integer|min:1',
            'courses'                               => 'sometimes|array',
            'courses.*.id'                          => 'required_with:courses.*|exists:courses,id',
            // 'courses.*.sessions_per_course'         => 'sometimes|integer|min:1',
            'categories'                            => 'nullable|array',
            'categories.*.id'                       => 'exists:categories,id',
            'categories.*.max_courses_per_category' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array {
        return [
            'name.required'                             => 'El campo nombre es obligatorio.',
            'name.string'                               => 'El nombre debe ser un texto válido.',
            'name.max'                                  => 'El nombre no puede exceder los 255 caracteres.',
            'description.string'                        => 'La descripción debe ser un texto válido.',
            'which_includes.required'                   => 'Debes proporcionar al menos un ítem.',
            'which_includes.*.string'                   => 'Cada ítem a aprender debe ser una cadena de texto.',
            'price.required'                            => 'El campo precio es obligatorio.',
            'price.numeric'                             => 'El precio debe ser un número válido.',
            'price.min'                                 => 'El precio no puede ser menor a cero.',
            'promotion_price.numeric'                   => 'El precio de promoción debe ser un número.',
            'promotion_price.min'                       => 'El precio de promoción no puede ser negativo.',
            'promotion_price.lt'                        => 'El precio de promoción debe ser menor al precio regular.',
            'course_limit.integer'                      => 'El límite debe ser un número entero',
            'seats.required'                            => 'El campo asientos es obligatorio.',
            'seats.integer'                             => 'La cantidad de asientos debe ser un número entero.',
            'seats.min'                                 => 'Debe haber al menos 1 asiento disponible.',
            'is_active.boolean'                         => 'El estado debe ser verdadero o falso.',
            'meta_description.max'                      => 'La meta descripción no puede exceder los 160 caracteres.',
            'meta_keywords.max'                         => 'Las meta palabras clave no pueden exceder los 1000 caracteres.',
            'courses.array'                             => 'El campo cursos debe ser una lista.',
            'courses.*.id.exists'                       => 'Uno de los IDs de curso seleccionados no existe en la base de datos.',
            // 'courses.*.sessions_per_course.integer'     => 'El número de sesiones por curso debe ser entero.',
            // 'courses.*.sessions_per_course.min'         => 'El número de sesiones por curso debe ser al menos 1.',
            'categories.array'                              => 'El campo categorías debe ser una lista.',
            'categories.*.id.exists'                        => 'Uno de los IDs de categoría seleccionados no existe en la base de datos.',
            'categories.*.max_courses_per_category.integer' => 'El máximo de cursos por categoría debe ser entero.',
            'categories.*.max_courses_per_category.min'     => 'El máximo de cursos por categoría debe ser al menos 1.',
        ];
    }

    protected function prepareForValidation() {
        // Decodificar which_includes si viene como string JSON
        if ($this->has('which_includes') && is_string($this->input('which_includes'))) {
            $whichIncludes = json_decode($this->input('which_includes'), true);
            $this->merge(['which_includes' => $whichIncludes ?? []]);
        }

        // Decodificar courses si viene como string JSON
        if ($this->has('courses') && is_string($this->input('courses'))) {
            $courses = json_decode($this->input('courses'), true);
            $this->merge(['courses' => $courses ?? []]);
        }

        // Decodificar categories si viene como string JSON
        if ($this->has('categories') && is_string($this->input('categories'))) {
            $categories = json_decode($this->input('categories'), true);
            $this->merge(['categories' => $categories ?? []]);
        }

        // Asegurar que which_includes siempre sea array
        if ($this->has('which_includes') && !is_array($this->input('which_includes'))) {
            $this->merge(['which_includes' => []]);
        }

        $this->merge([
            'course_limit' => $this->has('course_limit') ? filter_var($this->course_limit, FILTER_VALIDATE_INT) : 0,
        ]);
    }

    /**
     * Validación adicional después de la validación principal
     */
    public function withValidator($validator) {
        $validator->after(function ($validator) {
            // Validar cursos si existe
            if ($this->has('courses')) {
                $courses = $this->input('courses');
                
                if (!is_array($courses)) {
                    $validator->errors()->add('courses', 'El campo cursos debe ser una lista.');
                    return;
                }

                foreach ($courses as $index => $course) {
                    if (!isset($course['id']) || empty($course['id'])) {
                        $validator->errors()->add("courses.{$index}.id", "El curso #" . ($index + 1) . " debe tener un ID válido.");
                    } elseif (!Course::where('id', $course['id'])->exists()) {
                        $validator->errors()->add("courses.{$index}.id", "El curso #" . ($index + 1) . " no existe.");
                    }
                    
                    if (isset($course['quantity']) && (!is_numeric($course['quantity']) || $course['quantity'] < 1)) {
                        $validator->errors()->add("courses.{$index}.quantity", "La cantidad del curso #" . ($index + 1) . " debe ser un número mayor a 0.");
                    }
                }
            }

            // Validar categorías si existe
            if ($this->has('categories')) {
                $categories = $this->input('categories');
                
                if (!is_array($categories)) {
                    $validator->errors()->add('categories', 'El campo categorías debe ser una lista.');
                    return;
                }

                foreach ($categories as $index => $category) {
                    if (!isset($category['id']) || empty($category['id'])) {
                        $validator->errors()->add("categories.{$index}.id", "La categoría #" . ($index + 1) . " debe tener un ID válido.");
                    } elseif (!Category::where('id', $category['id'])->exists()) {
                        $validator->errors()->add("categories.{$index}.id", "La categoría #" . ($index + 1) . " no existe.");
                    }
                }
            }
        });
    }
}
