<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class MarcasComponent extends Component
{
    public $marcaSeleccionada = null;
    public $nombreMarca       = null;

    public function seleccionar($proveedorId, $nombre)
    {
        $this->marcaSeleccionada = $proveedorId;
        $this->nombreMarca       = $nombre;
    }

    public function limpiar()
    {
        $this->marcaSeleccionada = null;
        $this->nombreMarca       = null;
    }

    public function render()
    {
        // Agrupa proveedores que tienen al menos 1 producto
        $marcas = DB::table('proveedores')
            ->join('productos', 'proveedores.id', '=', 'productos.proveedor_id')
            ->select('proveedores.id', 'proveedores.nombre', DB::raw('COUNT(productos.id) as total'))
            ->groupBy('proveedores.id', 'proveedores.nombre')
            ->orderBy('proveedores.nombre')
            ->get();

        $productos = collect();
        if ($this->marcaSeleccionada) {
            $productos = Producto::where('proveedor_id', $this->marcaSeleccionada)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.marcas-component', compact('marcas', 'productos'))
            ->extends('layouts.toolbar')
            ->section('content');
    }

    public function agregarCarrito($productoId)
    {
        $producto = Producto::find($productoId);
        if (!$producto) {
            session()->flash('error', 'Producto no encontrado.');
            return;
        }

        try {
            $cartData = [
                'id'         => $producto->id,
                'name'       => $producto->nombre,
                'price'      => $producto->precio,
                'quantity'   => 1,
                'attributes' => ['image' => $producto->cover_img],
            ];

            auth()->check()
                ? \Cart::session(auth()->id())->add($cartData)
                : \Cart::add($cartData);
        } catch (\Throwable $e) {
            session()->flash('error', 'No se pudo agregar el producto.');
            return;
        }

        $this->emit('cartUpdated');
        $this->dispatchBrowserEvent('product-added', ['nombre' => $producto->nombre]);
        session()->flash('message', 'Producto agregado al carrito.');
    }
}
