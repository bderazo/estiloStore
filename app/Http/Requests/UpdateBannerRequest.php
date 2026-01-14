<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Banner;

class UpdateBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'seccion' => ['sometimes', 'string', Rule::in(array_keys(Banner::SECCIONES))],
            'titulo' => ['nullable', 'string', 'max:255'],
            'subtitulo' => ['nullable', 'string', 'max:255'],
            'imagen' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
            'url_destino' => ['nullable', 'url', 'max:255'],
            'estado' => ['sometimes', 'boolean'],
        ];
    }
}