<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamQuestionValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'question'          => 'required|string|max:1000',
            'type'              => 'required|in:multiple_choice,true_false',
            'points'            => 'required|integer|min:1|max:100',
            'options'           => 'required_if:type,multiple_choice',
            'correct_answer'    => 'required',
        ];
    }

    public function messages(): array {
        return [
            'question'          => 'required|string|max:1000',
            'type'              => 'required|in:multiple_choice,true_false',
            'points'            => 'required|integer|min:1|max:100',
            'options'           => 'required_if:type,multiple_choice',
            'correct_answer'    => 'required',
        ];
    }
}
