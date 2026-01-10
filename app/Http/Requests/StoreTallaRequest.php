<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTallaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                'unique:tallas,nombre'
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:500'
            ],
            'activo' => [
                'sometimes',
                'boolean'
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la talla es obligatorio.',
            'nombre.string' => 'El nombre de la talla debe ser una cadena de texto.',
            'nombre.max' => 'El nombre de la talla no puede tener más de 100 caracteres.',
            'nombre.unique' => 'Ya existe una talla con este nombre.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede tener más de 500 caracteres.',
            'activo.boolean' => 'El campo activo debe ser verdadero o falso.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convertir nombre a formato título (primera letra mayúscula)
        if ($this->has('nombre')) {
            $this->merge([
                'nombre' => ucwords(strtolower(trim($this->nombre)))
            ]);
        }

        // Asegurar que activo tenga un valor por defecto
        if (!$this->has('activo')) {
            $this->merge([
                'activo' => true
            ]);
        }

        // Limpiar descripción si está vacía
        if ($this->has('descripcion') && empty(trim($this->descripcion))) {
            $this->merge([
                'descripcion' => null
            ]);
        }
    }
}
