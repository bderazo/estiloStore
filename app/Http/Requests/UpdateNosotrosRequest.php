<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNosotrosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'cuerpo_principal' => ['nullable', 'string'],
            'cuerpo_secundario' => ['nullable', 'string'],
            'imagen_portada_url' => $this->imageRule('imagen_portada_url'),
            'imagen_cuerpo_1_url' => $this->imageRule('imagen_cuerpo_1_url'),
            'imagen_cuerpo_2_url' => $this->imageRule('imagen_cuerpo_2_url'),
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede superar 255 caracteres.',
            'imagen_portada_url.image' => 'La imagen de portada debe ser un archivo de imagen válido.',
            'imagen_portada_url.mimes' => 'La imagen de portada debe ser de tipo: jpeg, png, jpg, webp.',
            'imagen_portada_url.max' => 'La imagen de portada no puede superar los 4MB.',
            'imagen_cuerpo_1_url.image' => 'La imagen del cuerpo 1 debe ser un archivo de imagen válido.',
            'imagen_cuerpo_1_url.mimes' => 'La imagen del cuerpo 1 debe ser de tipo: jpeg, png, jpg, webp.',
            'imagen_cuerpo_1_url.max' => 'La imagen del cuerpo 1 no puede superar los 4MB.',
            'imagen_cuerpo_2_url.image' => 'La imagen del cuerpo 2 debe ser un archivo de imagen válido.',
            'imagen_cuerpo_2_url.mimes' => 'La imagen del cuerpo 2 debe ser de tipo: jpeg, png, jpg, webp.',
            'imagen_cuerpo_2_url.max' => 'La imagen del cuerpo 2 no puede superar los 4MB.',
        ];
    }

    private function imageRule(string $field): array
    {
        if ($this->hasFile($field)) {
            return ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'];
        }

        return ['nullable', 'string', 'max:2048'];
    }
}
