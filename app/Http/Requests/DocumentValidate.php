<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'course_id'     => 'required|exists:courses,id',
            'file'          => 'nullable|file|max:51200|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,7z',
            'is_active'     => 'boolean'
        ];
    }

    public function messages(): array {
        return [
            'title.required'    => 'El título es requerido.',
            'title.max'         => 'El título deber tener una longitud máxima de 255 caracteres.',
            'course_id.required' => 'El curso es requerido.',
            'course_id.exists'  => 'El curso no existe.',
            'file.file'         => 'El archivo seleccionado no tiene un formato válido.',
            'file.max'          => 'El archivo debe tener un pesó máximo de de 50 MB.',
            'file.mimes'        => 'El archivo debe tener los formatos indicados como pdf, doc, docx, ppt, pptx, xls, xlsx, txt, zip, rar, 7z.',
        ];
    }

    protected function prepareForValidation(){
        // Convertir checkboxes a booleanos
        $this->merge([
            'is_active' => $this->has('is_active') ? filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN) : false,
        ]);
    }
}
