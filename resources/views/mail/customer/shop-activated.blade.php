@component('mail::message')
# Felicitaciones

Tu tienda ya esta activa.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Gracias!,<br>
{{ config('app.name') }}
@endcomponent
