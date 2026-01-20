<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodeValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'discount_percentage' => 'nullable',
            'promotion_price_is_active' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation(){
        $this->merge([
            'promotion_price_is_active' => $this->has('promotion_price_is_active') ? filter_var($this->promotion_price_is_active, FILTER_VALIDATE_BOOLEAN) : false,
        ]);
    }
}
