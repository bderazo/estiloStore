<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlazaRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $plazaId = $this->route('plaza')?->id ?? null;

        return [
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:100',
                Rule::unique('plazas', 'nombre')->ignore($plazaId),
                'regex:/^[a-zA-Z0-9\s\-\_]+$/'
            ],
            'activo' => [
                'required',
                'boolean'
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la plaza es requerido',
            'nombre.string' => 'El nombre debe ser texto',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres',
            'nombre.unique' => 'Esta plaza ya existe',
            'nombre.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y guiones bajos',
            'activo.required' => 'El estado de la plaza es requerido',
            'activo.boolean' => 'El estado debe ser verdadero o falso',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim($this->nombre),
            'activo' => $this->activo ? true : false,
        ]);
    }
}
