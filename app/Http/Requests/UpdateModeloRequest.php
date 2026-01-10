<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateModeloRequest extends FormRequest
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
                'min:2',
                'max:100',
                Rule::unique('modelos', 'nombre')->ignore($this->modelo?->id),
                'regex:/^[\pL\pN\s\-_.(),\/áéíóúñüÁÉÍÓÚÑÜ]+$/u',
            ],
            'activo' => 'boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del modelo es requerido',
            'nombre.string' => 'El nombre debe ser texto',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres',
            'nombre.unique' => 'El nombre del modelo ya existe',
            'nombre.regex' => 'El nombre contiene caracteres inválidos',
            'activo.boolean' => 'El estado debe ser verdadero o falso',
        ];
    }
}
