@extends('layouts.toolbar')
@section('content')
    {{-- Componente Livewire para la página de Preguntas Frecuentes --}}
    @livewire('component-faq')
    {{-- Chatbox flotante, exclusivo para esta vista (no intrusivo) --}}
    @include('faq._chatbox')
@endsection
