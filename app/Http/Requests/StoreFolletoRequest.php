<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFolletoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo_pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
            'estado' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres',
            'archivo_pdf.required' => 'El archivo PDF es obligatorio',
            'archivo_pdf.mimes' => 'El archivo debe ser un PDF válido',
            'archivo_pdf.max' => 'El archivo no puede exceder los 10MB',
        ];
    }
}