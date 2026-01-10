<?php

namespace App\Services;

use App\Models\Nosotros;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NosotrosService
{
    public function getSingleton(): ?Nosotros
    {
        return Nosotros::query()->first();
    }

    public function updateOrCreate(array $attributes, array $files = []): Nosotros
    {
        $nosotros = $this->getSingleton() ?? new Nosotros();

        $imageFields = [
            'imagen_portada_url',
            'imagen_cuerpo_1_url',
            'imagen_cuerpo_2_url',
        ];

        foreach ($imageFields as $field) {
            if (array_key_exists($field, $files) && $files[$field] instanceof UploadedFile) {
                $uploadedFile = $files[$field];

                if ($nosotros->exists && $nosotros->{$field}) {
                    Storage::disk('public')->delete($nosotros->{$field});
                }

                $attributes[$field] = $uploadedFile->store('nosotros', 'public');
            }

            if (array_key_exists($field, $attributes) && $attributes[$field] === null && $nosotros->exists && $nosotros->{$field}) {
                Storage::disk('public')->delete($nosotros->{$field});
            }
        }

        $nosotros->fill($attributes);
        $nosotros->save();

        return $nosotros->fresh();
    }
}
