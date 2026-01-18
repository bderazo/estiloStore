<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFolletoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo_pdf' => 'sometimes|file|mimes:pdf|max:10240',
            'estado' => 'boolean',
            'reset_descargas' => 'boolean' // Opcional para resetear contador
        ];
    }
}