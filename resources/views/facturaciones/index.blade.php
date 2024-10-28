@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4 text-center">Listado de Facturación</h1>

        <div class="flex justify-end mb-3">
            <a href="{{ route('facturaciones.create') }}" class="btn btn-primary">Nueva Factura</a>
        </div>

        @if ($facturaciones->isEmpty())
            <p class="text-center text-gray-500">No hay facturas registradas.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 shadow-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Número de Factura</th>
                            <th class="py-3 px-6 text-left">Total</th>
                            <th class="py-3 px-6 text-left">Fecha de Creación</th>
                            <th class="py-3 px-6 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($facturaciones as $facturacion)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $facturacion->numero_factura }}</td>
                                <td class="py-3 px-6">${{ number_format($facturacion->total, 2) }}</td>
                                <td class="py-3 px-6">{{ $facturacion->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-6 flex space-x-2">
                                    <a href="{{ route('facturaciones.show', $facturacion->numero_factura) }}"
                                        class="btn btn-info">Ver Detalle</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
