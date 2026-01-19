<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use App\Http\Resources\MetodoPagoResource;
use App\Http\Resources\MetodoPagoCollection;
use App\Http\Requests\StoreMetodoPagoRequest;
use App\Http\Requests\UpdateMetodoPagoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MetodoPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MetodoPago::query();
        
        if ($request->has('tipo') && $request->tipo !== '') {
            $query->porTipo($request->tipo);
        }
        
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre_banco', 'like', "%{$search}%")
                ->orWhere('nombre_titular', 'like', "%{$search}%")
                ->orWhere('numero_cuenta', 'like', "%{$search}%");
            });
        }
        
        // Ordenamiento por defecto
        if ($request->has('sort_by') && $request->sort_by !== '') {
            $sortDir = $request->get('sort_dir', 'asc');
            $query->orderBy($request->sort_by, $sortDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Paginación o todos
        if ($request->has('per_page')) {
            $perPage = $request->get('per_page', 15);
            
            // Si per_page es 'all', devolver todos sin paginar
            if ($perPage === 'all') {
                $metodos = $query->get();
                return MetodoPagoResource::collection($metodos);
            }
            
            // Convertir a entero si es numérico
            $perPage = is_numeric($perPage) ? (int)$perPage : 15;
            $metodos = $query->paginate($perPage);
            return new MetodoPagoCollection($metodos);
        }
        
        $metodos = $query->get();
        return MetodoPagoResource::collection($metodos);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMetodoPagoRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $data = $request->validated();
            
            // Manejo de archivos
            if ($request->hasFile('imagen_qr')) {
                $data['imagen_qr'] = $request->file('imagen_qr')->store('metodos_pago/qr', 'public');
            }
            
            if ($request->hasFile('logo_banco')) {
                $data['logo_banco'] = $request->file('logo_banco')->store('metodos_pago/logos', 'public');
            }
            
            $metodoPago = MetodoPago::create($data);
            
            DB::commit();
            
            return response()->json([
                'message' => 'Método de pago creado exitosamente',
                'data' => new MetodoPagoResource($metodoPago)
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el método de pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id) // Cambia esto si estás usando route model binding
    {
        try {
            $metodoPago = MetodoPago::findOrFail($id);
            return new MetodoPagoResource($metodoPago);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Método de pago no encontrado',
                'error' => 'El método de pago con ID ' . $id . ' no existe'
            ], 404);
        }
    }
    
    public function update(UpdateMetodoPagoRequest $request, MetodoPago $metodos_pago)
{
    \Log::info('DEBUG - Actualizando método de pago:', [
        'id' => $metodos_pago->id,
        'nombre' => $metodos_pago->nombre_banco,
        'datos_recibidos' => $request->all()
    ]);
    
    // Resto del código igual pero usando $metodos_pago
    DB::beginTransaction();
    
    try {
        $data = $request->validated();
        
        // Manejo de eliminación de logo
        if ($request->has('remove_logo') && $request->remove_logo) {
            if ($metodos_pago->logo_banco) {
                Storage::disk('public')->delete($metodos_pago->logo_banco);
                $data['logo_banco'] = null;
            }
        }
        
        // Manejo de eliminación de QR
        if ($request->has('remove_qr') && $request->remove_qr) {
            if ($metodos_pago->imagen_qr) {
                Storage::disk('public')->delete($metodos_pago->imagen_qr);
                $data['imagen_qr'] = null;
            }
        }
        
        // Manejo de archivos nuevos
        if ($request->hasFile('imagen_qr')) {
            if ($metodos_pago->imagen_qr) {
                Storage::disk('public')->delete($metodos_pago->imagen_qr);
            }
            $data['imagen_qr'] = $request->file('imagen_qr')->store('metodos_pago/qr', 'public');
        }
        
        if ($request->hasFile('logo_banco')) {
            if ($metodos_pago->logo_banco) {
                Storage::disk('public')->delete($metodos_pago->logo_banco);
            }
            $data['logo_banco'] = $request->file('logo_banco')->store('metodos_pago/logos', 'public');
        }
        
        $metodos_pago->update($data);
        
        DB::commit();
        
        return response()->json([
            'message' => 'Método de pago actualizado exitosamente',
            'data' => new MetodoPagoResource($metodos_pago->fresh())
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al actualizar método de pago:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'message' => 'Error al actualizar el método de pago',
            'error' => $e->getMessage()
        ], 500);
    }
}
        
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MetodoPago $metodoPago)
    {
        DB::beginTransaction();
        
        try {
            // Eliminar archivos asociados
            if ($metodoPago->imagen_qr) {
                Storage::disk('public')->delete($metodoPago->imagen_qr);
            }
            
            if ($metodoPago->logo_banco) {
                Storage::disk('public')->delete($metodoPago->logo_banco);
            }
            
            $metodoPago->delete();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Método de pago eliminado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al eliminar el método de pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Activar/desactivar método de pago
     */
    public function toggleActivo(MetodoPago $metodoPago)
    {
        $metodoPago->update(['activo' => !$metodoPago->activo]);
        
        return response()->json([
            'message' => 'Estado actualizado exitosamente',
            'data' => new MetodoPagoResource($metodoPago)
        ]);
    }
    
    /**
     * Métodos activos para frontend público
     */
    public function activos()
    {
        $metodos = MetodoPago::activo()->orderBy('created_at', 'desc')->get();
        return MetodoPagoResource::collection($metodos);
    }
}