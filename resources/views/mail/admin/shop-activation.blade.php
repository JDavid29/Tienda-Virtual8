@component('mail::message')
# Solicitud de activacion de la tienda

Por favor activa tu tienda. Aqui estan los detalles de la tienda.
Nombre de la tienda: {{ $shop->name }}
Dueño de la tienda: {{ $shop->owner->name }}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
