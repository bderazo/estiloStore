<?php
namespace App\Services;
use App\Models\LegalDocument;

class LegalService {
    public function getDocumentUrl($tipo) {
        $doc = LegalDocument::where('tipo', $tipo)->where('activo', true)->first();
        return $doc ? asset('storage/' . $doc->archivo_url) : '#';
    }
}