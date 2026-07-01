{{--
    Vista: home/index.blade.php
    Layout: layouts.toolbar
    Componente Livewire: App\Http\Livewire\Inicio (index4.blade.php)

    CSS específico de esta página se inyecta en el <head> del layout
    mediante @push('styles') → @stack('styles') en toolbar.blade.php.
    El archivo public/css/pages/slider.css contiene únicamente las
    mejoras del slider; los estilos base siguen en public/style.css.
--}}
@extends('layouts.toolbar')

@push('styles')
    {{-- Estilos de mejora del slider: dots, badges, mini-cards de banner,
         descripción corta y estado vacío. Solo complementa style.css. --}}
    <link rel="stylesheet" href="{{ asset('css/pages/slider.css') }}">
@endpush

@section('content')
    @livewire('inicio')
@endsection
