<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMetodoPagoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambia esto según tu lógica de autorización
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('metodopago') ? $this->route('metodopago')->id : null;

        return [
            'nombre_banco' => 'required|string|max:255',
            'tipo_pago' => 'required|in:Transferencia,QR,Efectivo,Otro',
            'activo' => 'boolean',
            'instrucciones' => 'nullable|string',
            
            // Campos para Transferencia
            'nombre_titular' => 'required_if:tipo_pago,Transferencia|nullable|string|max:255',
            'numero_cuenta' => 'required_if:tipo_pago,Transferencia|nullable|string|max:255',
            'tipo_cuenta' => 'required_if:tipo_pago,Transferencia|nullable|in:Ahorros,Corriente,Vista,Jurídica,Otro',
            'identificacion' => 'nullable|string|max:255',
            
            // Archivos
            'logo_banco' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB
            'imagen_qr' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            
            // Flags para eliminar
            'remove_logo' => 'nullable|boolean',
            'remove_qr' => 'nullable|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convertir campos booleanos
        $this->merge([
            'activo' => filter_var($this->activo, FILTER_VALIDATE_BOOLEAN),
            'remove_logo' => filter_var($this->remove_logo, FILTER_VALIDATE_BOOLEAN),
            'remove_qr' => filter_var($this->remove_qr, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'nombre_banco.required' => 'El nombre del banco es obligatorio',
            'tipo_pago.required' => 'El tipo de pago es obligatorio',
            'tipo_pago.in' => 'El tipo de pago seleccionado no es válido',
            'nombre_titular.required_if' => 'El nombre del titular es obligatorio para transferencias',
            'numero_cuenta.required_if' => 'El número de cuenta es obligatorio para transferencias',
            'tipo_cuenta.required_if' => 'El tipo de cuenta es obligatorio para transferencias',
            'logo_banco.image' => 'El logo debe ser una imagen válida',
            'imagen_qr.image' => 'El QR debe ser una imagen válida',
            'logo_banco.max' => 'El logo no debe superar los 5MB',
            'imagen_qr.max' => 'El QR no debe superar los 5MB',
        ];
    }
}