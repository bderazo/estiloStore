<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMetodoPagoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        $rules = [
            'nombre_banco' => 'required|string|max:100',
            'tipo_pago' => [
                'required',
                Rule::in(['Transferencia', 'QR', 'Efectivo', 'Otro'])
            ],
            'nombre_titular' => 'nullable|string|max:150',
            'numero_cuenta' => 'nullable|string|max:50',
            'tipo_cuenta' => 'nullable|string|max:50',
            'identificacion' => 'nullable|string|max:20',
            'instrucciones' => 'nullable|string',
            'imagen_qr' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_banco' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'boolean',
        ];
        
        // Reglas condicionales según tipo de pago
        if ($this->input('tipo_pago') === 'Transferencia') {
            $rules['numero_cuenta'] = 'required|string|max:50';
            $rules['nombre_titular'] = 'required|string|max:150';
            $rules['tipo_cuenta'] = 'required|string|max:50';
        }
        
        if ($this->input('tipo_pago') === 'QR') {
            $rules['imagen_qr'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        
        return $rules;
    }
    
    public function messages()
    {
        return [
            'nombre_banco.required' => 'El nombre del banco es requerido',
            'tipo_pago.required' => 'El tipo de pago es requerido',
            'tipo_pago.in' => 'El tipo de pago seleccionado no es válido',
            'numero_cuenta.required' => 'El número de cuenta es requerido para transferencias',
            'nombre_titular.required' => 'El nombre del titular es requerido para transferencias',
            'tipo_cuenta.required' => 'El tipo de cuenta es requerido para transferencias',
            'imagen_qr.required' => 'La imagen QR es requerida para pagos QR',
            'imagen_qr.image' => 'El archivo QR debe ser una imagen',
            'imagen_qr.mimes' => 'El QR debe ser en formato: jpeg, png, jpg, gif',
            'imagen_qr.max' => 'El QR no debe pesar más de 2MB',
            'logo_banco.image' => 'El logo debe ser una imagen',
            'logo_banco.mimes' => 'El logo debe ser en formato: jpeg, png, jpg, gif',
            'logo_banco.max' => 'El logo no debe pesar más de 2MB'
        ];
    }
}