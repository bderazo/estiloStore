<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Banner;

class StoreBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'seccion' => ['required', 'string', Rule::in(array_keys(Banner::SECCIONES))],
            'titulo' => ['nullable', 'string', 'max:255'],
            'subtitulo' => ['nullable', 'string', 'max:255'],
            'imagen' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
            'url_destino' => ['nullable', 'url', 'max:255'],
            'estado' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'seccion.required' => 'La sección es obligatoria.',
            'seccion.in' => 'La sección seleccionada no es válida.',
            'imagen.required' => 'La imagen es obligatoria.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'imagen.max' => 'La imagen no debe pesar más de 5MB.',
            'url_destino.url' => 'La URL debe ser válida.',
        ];
    }
}