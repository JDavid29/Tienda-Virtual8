@extends('layouts.toolbar')

@section('content')
    {{-- Vista pública: resources/views/wishlist/index.blade.php --}}
    {{-- Esta vista extiende el layout `layouts.toolbar` y llama al componente Livewire `wishlist` --}}
    @livewire('wishlist')
@endsection
