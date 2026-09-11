<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

/**
 * Controlador para la edición rápida de stock inline.
 * Recibe el nuevo valor de cantidad vía AJAX desde el
 * listado de productos del panel admin y lo persiste.
 */
class ProductoStockController extends Controller
{
    public function update(Request $request, $id)
    {
        // Solo admins autenticados en Voyager pueden usar esta ruta
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            // La cantidad debe ser un entero no negativo
            'cantidad' => ['required', 'integer', 'min:0'],
        ]);

        $producto = Producto::findOrFail($id);
        $producto->cantidad = $request->cantidad;
        $producto->save();

        return response()->json([
            'success'  => true,
            'cantidad' => $producto->cantidad,
            'message'  => "Stock de \"{$producto->nombre}\" actualizado a {$producto->cantidad}",
        ]);
    }
}
