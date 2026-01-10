<?php

namespace App\Http\Requests\Modules;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateModuleRequest extends FormRequest
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
        $moduleId = $this->route('module')->id;
        
        return [
            'nombre' => ['required', 'string', Rule::unique('modules')->ignore($moduleId), 'max:100'],
            'slug' => ['required', 'string', Rule::unique('modules')->ignore($moduleId), 'max:100', 'regex:/^[a-z0-9-]+$/'],
            'descripcion' => ['nullable', 'string', 'max:255'],
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
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.unique' => 'Este nombre de módulo ya está registrado.',
            'nombre.max' => 'El nombre del módulo no puede tener más de :max caracteres.',
            'slug.required' => 'El campo slug es obligatorio.',
            'slug.unique' => 'Este slug ya está registrado.',
            'slug.max' => 'El slug no puede tener más de :max caracteres.',
            'slug.regex' => 'El slug solo puede contener letras minúsculas, números y guiones.',
            'descripcion.max' => 'La descripción no puede tener más de :max caracteres.',
        ];
    }
}