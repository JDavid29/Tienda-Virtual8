@extends('layouts.toolbar')
@section('content')
    {{--
        Corrección: Livewire busca componentes por su alias en kebab-case.
        La clase PHP es `App\Http\Livewire\ComponentShop3Column`, por convención
        su alias es `component-shop3-column`.
        Por eso llamamos al componente así:
        @livewire('component-shop3-column')

        Si hubieras colocado la clase en el namespace `App\Http\Livewire\Shop\ComponentShop3Column`
        entonces el alias sería `shop.component-shop3-column` y podrías usar
        @livewire('shop.component-shop3-column')
    --}}
    @livewire('component-shop3-column')
@endsection
