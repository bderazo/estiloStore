<?php

namespace App\Services;

use App\Models\Folletos;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FolletoService
{
    /**
     * Obtener folletos activos para la web
     */
    public function getFolletosActivos()
    {
        return Folletos::where('estado', true)->latest()->get();
    }

    /**
     * Guardar un nuevo folleto y su archivo
     */
    public function store(array $data, UploadedFile $file)
    {
        // Guardar archivo en storage/app/public/folletos
        $path = $file->store('folletos', 'public');
        
        $data['archivo_pdf'] = $path;
        return Folletos::create($data);
    }

    /**
     * Gestionar la descarga: Busca el archivo, incrementa el contador y retorna el path
     */
    public function procesarDescarga(int $id)
    {
        $folleto = Folletos::findOrFail($id);
        if (!Storage::disk('public')->exists($folleto->archivo_pdf)) {
            throw new \Exception("El archivo físico no existe.");
        }

        $folleto->descargas = $folleto->descargas + 1;
        $folleto->save(); 
        
        // Refrescamos para verificar que se guardó en la base de datos
        $folleto->refresh();

        // Retornamos la ruta completa para el controlador
        return storage_path('app/public/' . $folleto->archivo_pdf);
    }

    /**
     * Eliminar folleto y su archivo físico
     */
    public function delete(int $id)
    {
        $folleto = Folletos::findOrFail($id);
        
        if (Storage::disk('public')->exists($folleto->archivo_pdf)) {
            Storage::disk('public')->delete($folleto->archivo_pdf);
        }

        return $folleto->delete();
    }
    /**
     * Obtiene el folleto principal para mostrar en el header
     */
    public function getFolletoPrincipal()
    {
        return Folletos::where('estado', true)
            ->latest()
            ->first();
    }
}