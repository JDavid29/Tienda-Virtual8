@extends('layouts.toolbar')
@section('content')
    {{-- Componente Livewire para la vista de producto individual --}}
    @livewire('single-product-component', ['productId' => $id ?? null])
@endsection
