<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishlistItem; // Asumiendo un modelo llamado WishlistItem

class WishlistController extends Controller
{
    /**
     * Muestra todos los ítems de la lista de deseos del usuario.
     */
    public function index()
    {
        $items = WishlistItem::where('user_id', auth()->id())->get();
        return view('wishlist.index', ['items' => $items]);
    }

    /**
     * Almacena un nuevo ítem en la lista de deseos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        WishlistItem::create([
            'user_id' => auth()->id(),
            'product_id' => $validatedData['product_id'],
        ]);

        return redirect()->route('wishlist.index')->with('success', 'Ítem agregado a la lista de deseos.');
    }

    /**
     * Elimina un ítem de la lista de deseos.
     */
    public function destroy($id)
    {
        $item = WishlistItem::findOrFail($id);
        $item->delete();

        return redirect()->route('wishlist.index')->with('success', 'Ítem eliminado de la lista de deseos.');
    }
}
