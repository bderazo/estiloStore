<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero_documento', 'tipo_documento', 'nombres', 'apellidos', 'name', 
        'email', 'password', 'fecha_nacimiento', 'role_id', 
        'codigo_referido', 'referido_por_codigo', 'puntos_acumulados',
        'fallos_reserva', 'active', 'puntos_acumulados'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email' => 'encrypted',
        'numero_documento' => 'encrypted',
        'fecha_nacimiento' => 'date',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',         
        'active' => 'boolean',
        'puntos_acumulados' => 'integer'
    ];

    /**
     * Relación con el rol (un usuario tiene un rol)
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Obtener roles como collection para compatibilidad
     */
    public function getRolesCollection()
    {
        return collect($this->role ? [$this->role] : []);
    }



    /**
     * Obtener el identificador que se almacenará en el token JWT
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Obtener los claims personalizados del JWT
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->nombre === $roleName;
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function hasPermission(string $slug): bool
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->role->permissions()->where('slug', $slug)->exists();
    }

    /**
     * Obtener todos los permisos del usuario a través de su rol
     */
    public function getAllPermissions()
    {
        if (!$this->role) {
            return collect([]);
        }
        
        return $this->role->permissions()->with('module')->get();
    }

    /**
     * Asignar rol al usuario
     */
    public function assignRole(string $roleName): void
    {
        $role = Role::where('nombre', $roleName)->first();
        if ($role) {
            $this->update(['role_id' => $role->id]);
        }
    }

    /**
     * Remover rol del usuario
     */
    public function removeRole(): void
    {
        $this->update(['role_id' => null]);
    }

    /**
     * Obtener el nombre completo del usuario
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }
    public function direcciones() {
        return $this->hasMany(UserAddress::class);
    }
    public function transaccionesPuntos() {
        return $this->hasMany(PointTransaction::class);
    }
    /**
        * Método de conveniencia para saber el saldo real 
        * (aunque ya tienes el campo puntos_acumulados)
    */
    public function getSaldoPuntosAttribute()
    {
        return $this->transaccionesPuntos()->sum('cantidad');
    }
    public function tienePerfilCompleto(): bool
    {
        return !empty($this->numero_documento) && !empty($this->fecha_nacimiento);
    }
 //Relación con sus órdenes
public function orders() {
    return $this->hasMany(Order::class);
}

// Relación con el historial de puntos que creamos al inicio
public function pointTransactions() {
    return $this->hasMany(PointTransaction::class);
}

// Método para verificar si puede seguir comprando
public function canOrder() {
    return $this->active && $this->fallos_reserva < 2;
}   
}
