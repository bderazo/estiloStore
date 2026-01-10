<?php

namespace App\Services;

use App\Models\EmpresaDato;

class EmpresaService
{
    public function getDatosNosotros()
    {
        // Obtenemos los datos ordenados para que el foreach en el Blade respete tu jerarquía
        return EmpresaDato::active()
            ->orderBy('orden', 'asc')
            ->get();
    }
}
