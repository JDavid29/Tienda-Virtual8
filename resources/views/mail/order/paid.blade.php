@component('mail::message')
# Factura Pagada

Gracias por la compra
Aqui esta su recibo

<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Precio</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
        <tr>
            <td scope="row">{{ $item->name }}</td>
            <td>{{ $item->pivot->quantity }}</td>
            <td>{{ $item->pivot->price }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

Total : BOB. {{ $order->total }}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
