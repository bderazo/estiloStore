<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateColorRequest extends FormRequest
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
        $colorId = $this->route('color')->id ?? $this->route('color');

        return [
            'nombre' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('colores', 'nombre')->ignore($colorId)
            ],
            'codigo_hex' => [
                'sometimes',
                'required',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
                Rule::unique('colores', 'codigo_hex')->ignore($colorId)
            ],
            'activo' => [
                'sometimes',
                'boolean'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del color es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'nombre.unique' => 'Ya existe otro color con este nombre.',

            'codigo_hex.required' => 'El código hexadecimal es obligatorio.',
            'codigo_hex.string' => 'El código hexadecimal debe ser una cadena de texto.',
            'codigo_hex.regex' => 'El código hexadecimal debe tener un formato válido (ej: #FF0000 o #F00).',
            'codigo_hex.unique' => 'Ya existe otro color con este código hexadecimal.',

            'activo.boolean' => 'El campo activo debe ser verdadero o falso.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del color',
            'codigo_hex' => 'código hexadecimal',
            'activo' => 'estado activo'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Asegurar que el código hex tenga formato correcto
        if ($this->has('codigo_hex')) {
            $codigo = $this->input('codigo_hex');

            // Agregar # si no lo tiene
            if (!str_starts_with($codigo, '#')) {
                $codigo = '#' . $codigo;
            }

            // Convertir a mayúsculas para consistencia
            $codigo = strtoupper($codigo);

            $this->merge([
                'codigo_hex' => $codigo
            ]);
        }
    }
}
