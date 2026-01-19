<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmpresaDatoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    // DEBUG: Ver qué datos llegan a la validación
    \Log::info('📋 Validación - Datos recibidos:', $this->all());
    \Log::info('📋 Validación - activo:', [
        'value' => $this->input('activo'),
        'type' => gettype($this->input('activo'))
    ]);

    $claves = array_keys(\App\Models\EmpresaDato::getClaves());

    return [
        'clave' => [
            'required',
            'string',
            'max:50',
            Rule::in($claves),
            'unique:empresa_datos,clave'
        ],
        'titulo' => 'nullable|string|max:255',
        'contenido' => 'nullable|string',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'activo' => 'required|boolean',
        'orden' => 'integer|min:0'
    ];
}

protected function prepareForValidation()
{
    // DEBUG
    \Log::info('🛠️ prepareForValidation - activo antes:', [
        'value' => $this->input('activo'),
        'type' => gettype($this->input('activo'))
    ]);

    // Convertir '1'/'0' strings a boolean
    if ($this->has('activo')) {
        $activo = $this->input('activo');
        
        if (is_string($activo)) {
            $this->merge([
                'activo' => $activo === '1' || $activo === 'true',
            ]);
        }
    }
    
    // También convertir orden a integer
    if ($this->has('orden')) {
        $orden = $this->input('orden');
        \Log::info('🛠️ prepareForValidation - orden:', [
            'original' => $orden,
            'original_type' => gettype($orden)
        ]);
        
        $this->merge([
            'orden' => (int) $orden,
        ]);
    }
}

    public function messages(): array
    {
        return [
            'clave.in' => 'La clave seleccionada no es válida.',
            'clave.unique' => 'Esta clave ya está en uso.',
            'imagen.max' => 'La imagen no debe superar los 5MB.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.'
        ];
    }
}