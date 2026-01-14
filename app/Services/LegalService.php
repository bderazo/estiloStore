<?php
namespace App\Services;
use App\Models\LegalDocument;

class LegalService {
    public function getDocumentUrl($tipo) {
        $doc = LegalDocument::where('tipo', $tipo)
                            ->where('activo', true)
                            ->first();
        
        // Si existe, devuelve la URL de storage, si no, un link vacío
        return $doc ? asset('storage/' . $doc->archivo_url) : '#';
    }
}