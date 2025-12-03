<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ListaDeDeseo;
use App\Models\Producto;

class WishlistController extends Controller
{
    /**
     * Mostrar los ítems de la lista de deseos del usuario.
     */
    public function index()
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login.client');
        }

        $items = ListaDeDeseo::with('producto')->where('user_id', $user->id)->get();
        return view('wishlist.index', ['items' => $items]);
    }

    /**
     * Almacenar un nuevo ítem en la lista de deseos.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login.client');
        }

        $request->validate([
            'producto_id' => 'required|exists:productos,id',
        ]);

        ListaDeDeseo::firstOrCreate([
            'user_id' => $user->id,
            'producto_id' => $request->input('producto_id'),
        ]);

        return redirect()->back()->with('success', 'Producto agregado a la lista de deseos.');
    }

    /**
     * Eliminar un ítem de la lista de deseos.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login.client');
        }

        $item = ListaDeDeseo::where('id', $id)->where('user_id', $user->id)->first();
        if ($item) {
            $item->delete();
        }

        return redirect()->back()->with('success', 'Ítem eliminado.');
    }
}
