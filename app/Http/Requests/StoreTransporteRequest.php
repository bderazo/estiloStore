<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransporteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'ruta' => [
                'required',
                'string',
                'max:100',
                Rule::unique('transportes')->where(function ($query) {
                    return $query->where('cooperativa', $this->cooperativa);
                })
            ],
            'precio' => 'required|numeric|min:0|max:1000',
            'cooperativa' => 'required|string|max:100',
            'estado' => 'boolean',
            'tiempo_estimado' => 'nullable|numeric|min:0|max:72'
        ];
    }
    
    public function messages(): array
    {
        return [
            'ruta.unique' => 'Ya existe una ruta con esta cooperativa.',
            'precio.min' => 'El precio debe ser mayor o igual a 0.',
            'precio.max' => 'El precio no puede exceder $1000.'
        ];
    }
}