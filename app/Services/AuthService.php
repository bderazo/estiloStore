<?php

namespace App\Services;

use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerUser(array $data)
    {
        // 1. Validar Cédula Ecuatoriana si aplica
        if ($data['tipo_documento'] === 'CEDULA') {
            if (!$this->validarCedulaEcuador($data['numero_documento'])) {
                throw new \Exception("La cédula no es válida para Ecuador.");
            }
        }

        return DB::transaction(function () use ($data) {
            // 2. Generar código de referido único (8 caracteres, Mayúsculas y Números, sin Ñ)
            $data['codigo_referido'] = $this->generateUniqueCode();
            $data['password'] = Hash::make($data['password']);

            // Campo 'name' concatenado para compatibilidad Laravel
            $data['name'] = $data['nombres'] . ' ' . $data['apellidos'];

            // 3. Crear el usuario (El modelo User debe tener los casts de encriptación)
            $user = User::create($data);

            // 4. Lógica de Puntos por Bienvenida
            $this->addPoints($user, 10, 'Bono de Bienvenida');

            // 5. Lógica de Referido (Si alguien lo invitó)
            if (!empty($data['referido_por_codigo'])) {
                $padrino = User::where('codigo_referido', $data['referido_por_codigo'])->first();
                if ($padrino) {
                    $this->addPoints($padrino, 50, 'Bono por referir a ' . $user->name, $user);
                }
            }

            // 6. Enviar correo de verificación (Opcional si usas MustVerifyEmail)
            //$user->sendEmailVerificationNotification();

            return $user;
        });
    }

    private function generateUniqueCode()
    {
        do {
            // Genera 8 caracteres, quita la Ñ y caracteres confusos
            $code = substr(str_replace(['Ñ', 'I', 'O', '0', '1'], ['X', 'A', 'B', '2', '3'], strtoupper(Str::random(10))), 0, 8);
        } while (User::where('codigo_referido', $code)->exists());

        return $code;
    }

    private function addPoints($user, $puntos, $motivo, $referencia = null)
    {
        // Log de puntos
        PointTransaction::create([
            'user_id' => $user->id,
            'cantidad' => $puntos,
            'motivo' => $motivo,
            'reference_type' => $referencia ? get_class($referencia) : null,
            'reference_id' => $referencia ? $referencia->id : null,
        ]);

        // Actualizar balance en el usuario
        $user->increment('puntos_acumulados', $puntos);
    }

    private function validarCedulaEcuador($cedula)
    {
        if (strlen($cedula) != 10) return false;
        $digitos = str_split($cedula);
        $provincia = (int)($digitos[0] . $digitos[1]);
        if ($provincia < 1 || $provincia > 24) return false;

        $suma = 0;
        foreach (array_slice($digitos, 0, 9) as $key => $val) {
            $mult = ($key % 2 == 0) ? (int)$val * 2 : (int)$val;
            if ($mult > 9) $mult -= 9;
            $suma += $mult;
        }
        $verificador = (10 - ($suma % 10)) % 10;
        return $verificador == (int)$digitos[9];
    }
    // Añadir a app/Services/AuthService.php

    public function login(array $credentials, bool $remember = false)
    {
        // Buscamos el usuario por email (recordando que el email está encriptado en el cast)
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            request()->session()->regenerate();
            return true;
        }

        return false;
    }
}
