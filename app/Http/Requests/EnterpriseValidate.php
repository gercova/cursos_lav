<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterpriseValidate extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'ruc'                       => 'required|digits:11',
            'company_name'              => 'required|string|max:255',
            'trade_name'                => 'required|string|max:255',
            'legal_representative_dni'  => 'nullable|digits:8',
            'legal_representative'      => 'nullable|string|max:255',
            'address'                   => 'required|string|max:500',
            'geographical_code'         => 'nullable|string|max:10',
            'city'                      => 'required|string|max:100',
            'business_sector'           => 'nullable|string|max:255',
            'phrase'                    => 'nullable|string|max:500',
            'description'               => 'nullable|string',
            'vision'                    => 'nullable|string',
            'mission'                   => 'nullable|string',
            'phone_number_1'            => 'required|string|max:20',
            'phone_number_2'            => 'nullable|string|max:20',
            'email'                     => 'required|email|max:100',
            'facebook_link'             => 'nullable|url|max:255',
            'linkedin_link'             => 'nullable|url|max:255',
            'twitter_link'              => 'nullable|url|max:255',
            'instagram_link'            => 'nullable|url|max:255',
            'whatsapp_link'             => 'nullable|string|max:255',
            'logo'                      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon'                   => 'nullable|image|mimes:ico,png|max:1024',
        ];
    }

    public function messages(): array {
        return [
            'ruc.required'                      => 'El RUC es requerido',
            'ruc.digits'                        => 'El RUC debe ser de 11 dígitos',
            'company_name.required'             => 'La Razón Social es requerida',
            'company_name.max'                  => 'La Razón Social tiene una longitud máxima de 255 caracteres',
            'trade_name.required'               => 'El Nombre Comercial es requerido',
            'trade_name.max'                    => 'El Nombre Comercial tiene una longitud máxima de 255 caracteres',
            'legal_representative_dni.digits'   => 'El DNI debe tener 8 dígitos',
            'legal_representative.max'          => 'El Representante Legal tiene una longitud máxima de 255 caracteres',
            'address.max'                       => 'La Dirección tiene una longitud máxima de 500 caracteres',
            'geographical_code.max'             => 'El Código Postal tiene una longitud máxima de 10 caracteres',
            'city.required'                     => 'La Ciudad es requerida',
            'city.max'                          => 'La Ciudad tiene una longitud máxima de 100 caracteres',
            'business_sector.max'               => 'El Rubro del Negocio tiene una longitud máxima de 255 caracteres',
            'phrase.max'                        => 'La Frase tiene una longitud máxima de 500 caracteres',
            'phone_number_1.required'           => 'El Teléfono Principal es requerido',
            'phone_number_1.max'                => 'El Teléfono Principal tiene una longitud máxima de 20 caracteres',
            'phone_number_2.max'                => 'El Teléfono Secundario tiene una longitud máxima de 20 caracteres',
            'email.required'                    => 'El E-mail es requerido',
            'email.email'                       => 'El E-mail no tiene el formato requerido',
            'email.max'                         => 'El E-mail tiene una longitud máxima de 100 caracteres',
            'facebook_link.url'                 => 'El enlace de Facebook debe ser una URL válida',
            'facebook_link.max'                 => 'El enlace de Facebook excede el máximo de caracteres',
            'linkedin_link.url'                 => 'El enlace de LinkedIn debe ser una URL válida',
            'linkedin_link.max'                 => 'El enlace de LinkedIn excede el máximo de caracteres',
            'twitter_link.url'                  => 'El enlace de Twitter debe ser una URL válida',
            'twitter_link.max'                  => 'El enlace de Twitter excede el máximo de caracteres',
            'instagram_link.url'                => 'El enlace de Instagram debe ser una URL válida',
            'instagram_link.max'                => 'El enlace de Instagram excede el máximo de caracteres',
            'whatsapp_link.max'                 => 'El enlace de Whatsapp excede el máximo de caracteres',
            'logo.image'                        => 'El Logo debe ser una imagen',
            'logo.mimes'                        => 'El Logo debe ser un archivo de tipo: jpeg, png, jpg, gif, svg',
            'logo.max'                          => 'El Logo no debe pesar más de 2MB',
            'favicon.image'                     => 'El Favicon debe ser una imagen',
            'favicon.mimes'                     => 'El Favicon debe ser un archivo de tipo: ico, png',
            'favicon.max'                       => 'El Favicon no debe pesar más de 1MB',
        ];
    }

    protected function prepareForValidation() {
        // Recorremos todos los inputs
        $data = $this->all();

        foreach ($data as $key => $value) {
            // Si el valor es un string y está vacío (o solo espacios), lo convertimos a NULL
            // Nota: Esto no afecta a los archivos (logo, favicon) porque no son strings.
            if (is_string($value) && trim($value) === '') {
                $data[$key] = null;
            }
        }

        // Reemplazamos la data original con la data limpia
        $this->merge($data);
    }
}
