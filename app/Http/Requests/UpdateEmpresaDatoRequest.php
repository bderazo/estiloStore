<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmpresaDatoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $claves = array_keys(\App\Models\EmpresaDato::getClaves());
        $empresaDatoId = $this->route('empresa_dato') ?? $this->route('id');

        return [
            'clave' => [
                'required',
                'string',
                'max:50',
                Rule::in($claves),
                Rule::unique('empresa_datos', 'clave')->ignore($empresaDatoId)
            ],
            'titulo' => 'nullable|string|max:255',
            'contenido' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'activo' => 'boolean',
            'orden' => 'integer|min:0'
        ];
    }
}