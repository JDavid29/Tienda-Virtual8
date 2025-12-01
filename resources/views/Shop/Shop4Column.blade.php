@extends('layouts.toolbar')

@section('content')
    {{--
        Corrección: Livewire busca componentes por su alias en kebab-case.
        La clase PHP es `App\Http\Livewire\ComponentShop4Column`, por convención
        su alias es `component-shop4-column`.
        Por eso llamamos al componente así:
        @livewire('component-shop4-column')

        Si hubieras colocado la clase en el namespace `App\Http\Livewire\Shop\ComponentShop4Column`
        entonces el alias sería `shop.component-shop4-column` y podrías usar
        @livewire('shop.component-shop4-column')
    --}}
    @livewire('component-shop4-column')
@endsection
