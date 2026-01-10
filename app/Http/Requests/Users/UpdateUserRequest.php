<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')->id;
        
        return [
            'numero_documento' => ['required', 'string', Rule::unique('users')->ignore($userId), 'max:20'],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($userId), 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'roles' => ['required', 'array', 'min:1', 'max:1'], // Solo un rol permitido
            'roles.*' => ['exists:roles,id'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'numero_documento.required' => 'El campo número de documento es obligatorio.',
            'numero_documento.unique' => 'Este número de documento ya está registrado.',
            'numero_documento.max' => 'El número de documento no puede tener más de :max caracteres.',
            'nombres.required' => 'El campo nombres es obligatorio.',
            'nombres.max' => 'El campo nombres no puede tener más de :max caracteres.',
            'apellidos.required' => 'El campo apellidos es obligatorio.',
            'apellidos.max' => 'El campo apellidos no puede tener más de :max caracteres.',
            'email.email' => 'El campo email debe ser una dirección de correo válida.',
            'email.unique' => 'Este email ya está registrado.',
            'email.max' => 'El campo email no puede tener más de :max caracteres.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'roles.array' => 'Los roles deben ser un arreglo válido.',
            'roles.*.exists' => 'Uno o más roles seleccionados no existen.',
        ];
    }
}