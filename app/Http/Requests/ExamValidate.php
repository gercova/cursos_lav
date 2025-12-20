<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'course_id'     => 'required|exists:courses,id',
            'passing_score' => 'required|numeric|min:14|max:20',
            'duration'      => 'nullable|integer|min:30',
            'max_attempts'  => 'nullable|integer|min:1',
            'is_final'      => 'boolean',
            'show_results'  => 'boolean',
            'randomize_questions' => 'boolean',
            'is_active'     => 'boolean',
        ];
    }

    public function messages(): array {
        return [
            'title.required'            => 'El título es requerido.',
            'title.max'                 => 'El título tiene una longitud máxima de 255 caracteres.',
            'description.required'      => 'La descripción es requerida.',
            'course_id.required'        => 'El curso es requerido.',
            'course_id.exists'          => 'El curso no existe.',
            'passing_score.required'    => 'El puntaje mínimo es requerido.',
            'passing_score.min'         => 'La nota mínima es 14.',
            'passing_score.max'         => 'La nota máxima es 20.',
            'duration.integer'          => 'La tiempo límite del examen debe ser entero.',
            'duration.min'              => 'El tiempo límite debe ser mayor o igual a 30 min.',
            'max_attempts.integer'      => 'El número de intentos debe ser un número entero.',
            'max_attempts.min'          => 'El número de intentos mínimo debe ser 1.'

        ];
    }

    protected function prepareForValidation(){
        $passingScore = $this->passing_score;

        // Si viene como "14.00", conviértelo a entero
        if (is_string($passingScore)) {
            $passingScore = floatval($passingScore); // convierte "14.00" → 14.0
            $passingScore = (int) $passingScore;     // → 14
        }
        // Convertir checkboxes a booleanos
        $this->merge([
            'passing_score'         => $this->filled('passing_score') ? floatval($this->passing_score) : 14.0,
            'duration'              => $this->has('duration') ? filter_var($this->duration, FILTER_VALIDATE_INT): 30,
            'max_attempts'          => $this->has('max_attempts') ? filter_var($this->max_attempts, FILTER_VALIDATE_INT) : 1,
            'is_final'              => $this->has('is_final') ? filter_var($this->is_final, FILTER_VALIDATE_BOOLEAN) : false,
            'show_results'          => $this->has('show_results') ? filter_var($this->show_results, FILTER_VALIDATE_BOOLEAN) : false,
            'randomize_questions'   => $this->has('randomize_questions') ? filter_var($this->randomize_questions, FILTER_VALIDATE_BOOLEAN) : false,
            'is_active'             => $this->has('is_active') ? filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN) : false,
        ]);
    }
}
