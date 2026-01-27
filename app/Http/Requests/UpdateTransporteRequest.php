<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransporteRequest extends FormRequest
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
                })->ignore($this->route('transporte'))
            ],
            'precio' => 'required|numeric|min:0|max:1000',
            'cooperativa' => 'required|string|max:100',
            'estado' => 'boolean',
            'tiempo_estimado' => 'nullable|numeric|min:0|max:72'
        ];
    }
}