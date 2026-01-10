<?php

namespace App\Http\Requests\Articulo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticuloRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: Implementar lógica de autorización (políticas)
        return true;
    }

    /**
     * Prepare data for validation - decode JSON strings when sent via FormData
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Decodificar strings JSON que vienen de FormData
        if (isset($data['variantes']) && is_string($data['variantes'])) {
            $data['variantes'] = json_decode($data['variantes'], true);
        }

        if (isset($data['imagenes_eliminar']) && is_string($data['imagenes_eliminar'])) {
            $data['imagenes_eliminar'] = json_decode($data['imagenes_eliminar'], true);
        }

        if (isset($data['imagenes_orden']) && is_string($data['imagenes_orden'])) {
            $data['imagenes_orden'] = json_decode($data['imagenes_orden'], true);
        }

        if (isset($data['variantes_eliminar']) && is_string($data['variantes_eliminar'])) {
            $data['variantes_eliminar'] = json_decode($data['variantes_eliminar'], true);
        }

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:articulos,slug'],
            'descripcion' => ['nullable', 'string'],
            'especificaciones' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:articulos,sku'],
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'marca_id' => ['nullable', 'exists:marcas,id'],
            'activo' => ['nullable', 'boolean'],
            'destacado' => ['nullable', 'boolean'],
            'imagenes' => ['nullable', 'array', 'max:10'],
            'imagenes.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'imagenes_eliminar' => ['nullable', 'array'],
            'imagenes_eliminar.*' => ['integer'],
            'imagenes_orden' => ['nullable', 'array'],
            'imagenes_orden.*' => ['integer'],
            'variantes' => ['nullable', 'array'],
            'variantes.*.atributos' => ['required', 'array'],
            'variantes.*.sku' => ['nullable', 'string', 'max:255', 'unique:articulo_variantes,sku'],
            'variantes.*.precio' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'variantes.*.stock' => ['required', 'integer', 'min:0'],
            'variantes.*.reserved' => ['nullable', 'integer', 'min:0'],
            'variantes.*.activo' => ['boolean'],
            'variantes_eliminar' => ['nullable', 'array'],
            'variantes_eliminar.*' => ['integer'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede superar 255 caracteres.',

            'slug.required' => 'El campo slug es obligatorio.',
            'slug.unique' => 'El slug ya está en uso.',
            'slug.max' => 'El slug no puede superar 255 caracteres.',

            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'especificaciones.string' => 'Las especificaciones deben ser un texto válido.',

            'precio.required' => 'El campo precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.min' => 'El precio debe ser mayor o igual a 0.',
            'precio.max' => 'El precio no puede superar 999999.99.',

            'sku.unique' => 'El SKU ya está en uso.',
            'sku.max' => 'El SKU no puede superar 255 caracteres.',

            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'marca_id.exists' => 'La marca seleccionada no es válida.',

            'imagenes.array' => 'Las imágenes deben ser un arreglo.',
            'imagenes.max' => 'No se pueden subir más de 10 imágenes.',
            'imagenes.*.image' => 'Cada archivo debe ser una imagen válida.',
            'imagenes.*.mimes' => 'Cada imagen debe ser de formato jpg, jpeg, png o gif.',
            'imagenes.*.max' => 'Cada imagen no debe superar los 5 MB.',

            'variantes.array' => 'Las variantes deben ser un arreglo.',
            'variantes.*.atributos.required' => 'Cada variante debe tener atributos.',
            'variantes.*.atributos.array' => 'Los atributos de la variante deben ser un arreglo.',
            'variantes.*.sku.unique' => 'El SKU de la variante ya está en uso.',
            'variantes.*.sku.max' => 'El SKU de la variante no puede superar 255 caracteres.',
            'variantes.*.precio.numeric' => 'El precio de la variante debe ser un número válido.',
            'variantes.*.precio.min' => 'El precio de la variante debe ser mayor o igual a 0.',
            'variantes.*.precio.max' => 'El precio de la variante no puede superar 999999.99.',
            'variantes.*.stock.required' => 'El stock de cada variante es obligatorio.',
            'variantes.*.stock.integer' => 'El stock debe ser un número entero.',
            'variantes.*.stock.min' => 'El stock debe ser mayor o igual a 0.',
            'variantes.*.reserved.integer' => 'El stock reservado debe ser un número entero.',
            'variantes.*.reserved.min' => 'El stock reservado debe ser mayor o igual a 0.',
            'imagenes_eliminar.array' => 'Las imágenes a eliminar deben ser un arreglo.',
            'imagenes_orden.array' => 'El campo imagenes orden debe ser un arreglo.',
            'variantes_eliminar.array' => 'Las variantes a eliminar deben ser un arreglo.',
        ];
    }
}
