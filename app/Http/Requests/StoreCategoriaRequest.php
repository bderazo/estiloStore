<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoriaRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categorias,slug',
            'descripcion' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:categorias,id',
            'activo' => 'boolean',
            'orden' => 'nullable|integer|min:0'
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
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'slug.required' => 'El slug es obligatorio.',
            'slug.string' => 'El slug debe ser una cadena de texto.',
            'slug.max' => 'El slug no puede exceder los 255 caracteres.',
            'slug.unique' => 'Este slug ya está en uso.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede exceder los 1000 caracteres.',
            'parent_id.exists' => 'La categoría padre seleccionada no existe.',
            'orden.integer' => 'El orden debe ser un número entero.',
            'orden.min' => 'El orden debe ser mayor o igual a 0.'
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
            'nombre' => 'nombre',
            'slug' => 'slug',
            'descripcion' => 'descripción',
            'parent_id' => 'categoría padre',
            'activo' => 'estado activo',
            'orden' => 'orden'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Generar slug automáticamente si no se proporciona
        if (!$this->has('slug') && $this->has('nombre')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->nombre)
            ]);
        }

        // Convertir string vacío a null para parent_id
        if ($this->parent_id === '') {
            $this->merge(['parent_id' => null]);
        }
    }
}
