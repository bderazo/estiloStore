<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model {
    protected $fillable = ['tipo', 'archivo_url', 'version', 'activo'];
}