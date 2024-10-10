<!-- resources/views/facturaciones/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Facturación</h1>

        <a href="{{ route('facturaciones.create') }}" class="btn btn-primary mb-3">Nueva Factura</a>

        @if ($facturaciones->isEmpty())
            <p>No hay facturas registradas.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($facturaciones as $facturacion)
                        <tr>
                            <td>{{ $facturacion->inventario->nombre }}</td>
                            <td>{{ $facturacion->cantidad }}</td>
                            <td>{{ $facturacion->total }}</td>
                            <td>{{ $facturacion->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
