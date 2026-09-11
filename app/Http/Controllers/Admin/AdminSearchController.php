<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Búsqueda global del panel admin.
 *
 * GET /admin/search?q=...
 *
 * Busca simultáneamente en:
 *   - productos (nombre, descripcion)
 *   - pedidos / orders (id de cliente, email, estado)
 *
 * Devuelve JSON con los grupos "productos" y "pedidos".
 * Máximo 5 resultados por grupo para mantener el dropdown ligero.
 */
class AdminSearchController extends Controller
{
    /** Número máximo de resultados por grupo */
    const PER_GROUP = 5;

    public function search(Request $request)
    {
        // Solo admins pueden usar esta ruta
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $q = trim($request->get('q', ''));

        // Mínimo 2 caracteres para buscar
        if (mb_strlen($q) < 2) {
            return response()->json(['productos' => [], 'pedidos' => []]);
        }

        // ── Buscar productos ─────────────────────────────────────────
        $productos = Producto::with('category')
            ->where(function ($query) use ($q) {
                $query->where('nombre', 'LIKE', "%{$q}%")
                      ->orWhere('descripcion', 'LIKE', "%{$q}%");
            })
            ->select('id', 'nombre', 'precio', 'cantidad', 'cover_img', 'category_id')
            ->limit(self::PER_GROUP)
            ->get()
            ->map(function ($p) {
                return [
                    'id'       => $p->id,
                    'nombre'   => $p->nombre,
                    'precio'   => $p->precio,
                    'cantidad' => $p->cantidad,
                    'cover_img'=> $p->cover_img,
                    'categoria'=> $p->category ? $p->category->name : null,
                ];
            });

        // ── Buscar pedidos (tabla orders) ────────────────────────────
        // Buscamos por ID, estado, o nombre/email del usuario relacionado
        $pedidos = DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->where(function ($query) use ($q) {
                $query->where('orders.id', 'LIKE', "%{$q}%")
                      ->orWhere('orders.status', 'LIKE', "%{$q}%")
                      ->orWhere('users.name', 'LIKE', "%{$q}%")
                      ->orWhere('users.email', 'LIKE', "%{$q}%");
            })
            ->select(
                'orders.id',
                'orders.status',
                'orders.total',
                'orders.created_at',
                'users.name as cliente'
            )
            ->orderByDesc('orders.created_at')
            ->limit(self::PER_GROUP)
            ->get();

        return response()->json([
            'productos' => $productos,
            'pedidos'   => $pedidos,
        ]);
    }
}
