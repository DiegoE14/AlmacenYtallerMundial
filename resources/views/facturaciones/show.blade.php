@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4 text-center">Detalle de la Factura #{{ $factura->numero_factura }}</h1>

        <div class="mb-4">
            <h2 class="text-xl font-semibold">Información General</h2>
            <p><strong>Total: </strong>${{ number_format($totalFactura, 2) }}</p> <!-- Usar totalFactura -->
            <p><strong>Fecha de Creación: </strong>{{ $factura->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Ver PDF: </strong>
                <a href="{{ route('facturaciones.pdf', $factura->numero_factura) }}" class="text-blue-500 hover:underline"
                    target="_blank">
                    Descargar PDF
                </a>
            </p>
        </div>

        <h2 class="text-xl font-semibold mb-2">Productos de la Factura</h2>
        @if ($productos->isEmpty())
            <p class="text-gray-500">No hay productos registrados en esta factura.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 shadow-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Producto</th>
                            <th class="py-3 px-6 text-left">Cantidad</th>
                            <th class="py-3 px-6 text-left">Valor Final</th>
                            <th class="py-3 px-6 text-left">Descuento</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($productos as $producto)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $producto->inventario->nombre }}</td>
                                <td class="py-3 px-6">{{ $producto->cantidad }}</td>
                                <td class="py-3 px-6">${{ number_format($producto->valor_final, 2) }}</td>
                                <td class="py-3 px-6">{{ $producto->descuento == 1 ? '10%' : '0%' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('facturaciones.index') }}" class="btn btn-primary">Regresar al Listado</a>
        </div>
    </div>
@endsection
