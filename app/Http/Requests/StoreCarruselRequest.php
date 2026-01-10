<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarruselRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'nullable|string|max:500',
            'activar_subtitulo' => 'boolean',
            'activar_boton' => 'boolean',
            'url_boton' => 'nullable|string|max:255',
            'redirigir_misma_pagina' => 'boolean',
            'posicion_contenido' => 'required|in:Izquierda,Derecha',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'estado' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.string' => 'El título debe ser una cadena de texto.',
            'titulo.max' => 'El título no puede exceder los 255 caracteres.',
            'subtitulo.string' => 'El subtítulo debe ser una cadena de texto.',
            'subtitulo.max' => 'El subtítulo no puede exceder los 500 caracteres.',
            'url_boton.string' => 'La URL del botón debe ser una cadena de texto.',
            'url_boton.max' => 'La URL del botón no puede exceder los 255 caracteres.',
            'posicion_contenido.required' => 'La posición del contenido es obligatoria.',
            'posicion_contenido.in' => 'La posición del contenido debe ser Izquierda o Derecha.',
            'imagen.required' => 'La imagen es obligatoria.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
            'imagen.max' => 'La imagen no puede superar los 2MB.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'titulo' => 'título',
            'subtitulo' => 'subtítulo',
            'activar_subtitulo' => 'activar subtítulo',
            'activar_boton' => 'activar botón',
            'url_boton' => 'URL del botón',
            'redirigir_misma_pagina' => 'redirigir en la misma página',
            'posicion_contenido' => 'posición del contenido',
            'imagen' => 'imagen',
            'estado' => 'estado'
        ];
    }
}
