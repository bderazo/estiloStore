@extends('web.layouts.base')
@section('title', 'Título de la página')
@push('styles')
@endpush
    
@section('content')

       @include('web.index.nosotros', [
            'nosotros' => $nosotros,
            'imagenes' => $imagenes,
        ])
@endsection