@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4 text-center">Listado de Inventario</h1>

        <div class="flex justify-end mb-3">
            <a href="{{ route('inventarios.create') }}" class="btn btn-primary">Agregar Producto</a>
        </div>

        @if ($inventarios->isEmpty())
            <p class="text-center text-gray-500">No hay productos en el inventario.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 shadow-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Nombre</th>
                            <th class="py-3 px-6 text-left">Valor Venta</th>
                            <th class="py-3 px-6 text-left">Cantidad</th>
                            <th class="py-3 px-6 text-left">Lugar</th>
                            <th class="py-3 px-6 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($inventarios as $inventario)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $inventario->nombre }}</td>
                                <td class="py-3 px-6">{{ $inventario->valor_venta }}</td>
                                <td class="py-3 px-6">{{ $inventario->cantidad }}</td>
                                <td class="py-3 px-6">{{ $inventario->lugar }}</td>
                                <td class="py-3 px-6 flex space-x-2">
                                    <a href="{{ route('inventarios.edit', $inventario->id) }}"
                                        class="btn btn-warning">Editar</a>
                                    <form action="{{ route('inventarios.destroy', $inventario->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
