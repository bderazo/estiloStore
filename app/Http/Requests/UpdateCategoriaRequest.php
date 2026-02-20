<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:categorias,id',
            'activo' => 'required|boolean',
            'orden' => 'nullable|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imagen_eliminar' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_eliminar' => 'nullable|boolean',
        ];
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'slug.required' => 'El slug es obligatorio.',
            'slug.string' => 'El slug debe ser una cadena de texto.',
            'slug.max' => 'El slug no puede exceder los 255 caracteres.',
            'slug.unique' => 'Este slug ya está en uso.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede exceder los 1000 caracteres.',
            'parent_id.exists' => 'La categoría padre seleccionada no existe.',
            'parent_id.not_in' => 'Una categoría no puede ser padre de sí misma.',
            'orden.integer' => 'El orden debe ser un número entero.',
            'orden.min' => 'El orden debe ser mayor o igual a 0.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'slug' => 'slug',
            'descripcion' => 'descripción',
            'logo' => 'logo', 
            'parent_id' => 'categoría padre',
            'activo' => 'estado activo',
            'orden' => 'orden'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Generar slug automáticamente si no se proporciona
        if (!$this->has('slug') && $this->has('nombre')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->nombre)
            ]);
        }

        // Convertir string vacío a null para parent_id
        if ($this->parent_id === '') {
            $this->merge(['parent_id' => null]);
        }
        
        // Convertir string vacío a null para logo
        if ($this->has('logo') && $this->logo === '') {
            $this->merge(['logo' => null]);
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $categoriaId = $this->route('categoria');
            $parentId = $this->parent_id;

            // Validar que no se cree una referencia circular
            if ($parentId && $this->wouldCreateCircularReference($categoriaId, $parentId)) {
                $validator->errors()->add('parent_id', 'No se puede crear una referencia circular en las categorías.');
            }
        });
    }

    /**
     * Check if assigning parent would create circular reference
     */
    private function wouldCreateCircularReference($categoriaId, $parentId): bool
    {
        $categoria = \App\Models\Categoria::find($categoriaId);
        if (!$categoria)
            return false;

        // Verificar si el parent_id es un descendiente de la categoría actual
        $descendants = $this->getDescendants($categoriaId);
        return in_array($parentId, $descendants->pluck('id')->toArray());
    }

    /**
     * Get all descendants of a category
     */
    private function getDescendants($categoriaId)
    {
        return \App\Models\Categoria::where('parent_id', $categoriaId)
            ->with('children')
            ->get()
            ->flatMap(function ($child) {
                return collect([$child])->merge($this->getDescendants($child->id));
            });
    }
}
