<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class MiCuentaComponent extends Component
{
    use WithPagination;

    public $tab          = 'perfil';
    public $filtroEstado = 'todos';
    public $pedidoAbierto = null; // id del pedido expandido

    protected $queryString = ['tab', 'filtroEstado'];

    public function cambiarTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function setFiltro($estado)
    {
        $this->filtroEstado = $estado;
        $this->resetPage();
    }

    public function togglePedido($id)
    {
        $this->pedidoAbierto = $this->pedidoAbierto === $id ? null : $id;
    }

    public function render()
    {
        $user = auth()->user();

        $query = Order::with('items')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($this->filtroEstado !== 'todos') {
            $query->where('status', $this->filtroEstado);
        }

        $pedidos     = $query->paginate(5);
        $totalPedidos = Order::where('user_id', $user->id)->count();

        return view('livewire.mi-cuenta-component', compact('user', 'pedidos', 'totalPedidos'))
            ->extends('layouts.toolbar')
            ->section('content');
    }
}
