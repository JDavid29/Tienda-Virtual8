<div>
    {{-- foreach del contenido del carrito --}}
    <div class="container">

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart_items->sortBy('id') as $key => $item)
                <tr>
                    <td>{{ $item->name }} </td>
                    <td>
                        <input type="number"
                        id="v{{ $item->id }}"
                        wire:change="update_quantity({{ $item->id }}, $('#v' + {{ $item->id }}).val())"
                        class="form-control" value="{{ $item->quantity }}">
                    </td>
                    <td>
                        {{ \Cart::session(auth()->id())->get($item->id)->getPriceSum() }}
                    <td>
                        <button type="button"
                        wire:click="delete_item( {{ $item->id }})"
                        class="btn btn-danger">Eliminar</button>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <h3>Total: BOB {{ \Cart::session(auth()->id())->getTotal() }}</h3>
    </div>
</div>
