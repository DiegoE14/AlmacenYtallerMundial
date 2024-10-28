<!-- resources/views/inventarios/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Agregar Producto al Inventario</h1>

        @if ($errors->any())
            <div class="alert alert-danger mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inventarios.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
            @csrf

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-semibold mb-2">Nombre del Producto</label>
                <input type="text" name="nombre" id="nombre" class="form-control w-full border rounded-md shadow-sm"
                    placeholder="Ingrese el nombre del producto" value="{{ old('nombre') }}" required>
            </div>

            <div class="mb-4">
                <label for="valor_venta" class="block text-gray-700 font-semibold mb-2">Valor de Venta</label>
                <input type="number" step="0.01" name="valor_venta" id="valor_venta"
                    class="form-control w-full border rounded-md shadow-sm" placeholder="Ingrese el valor de venta"
                    value="{{ old('valor_venta') }}" required>
            </div>

            <div class="mb-4">
                <label for="cantidad" class="block text-gray-700 font-semibold mb-2">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control w-full border rounded-md shadow-sm"
                    placeholder="Ingrese la cantidad disponible" value="{{ old('cantidad') }}" required>
            </div>

            <div class="mb-4">
                <label for="lugar" class="block text-gray-700 font-semibold mb-2">Ubicación en el Almacén</label>
                <input type="text" name="lugar" id="lugar" class="form-control w-full border rounded-md shadow-sm"
                    placeholder="Ejemplo: Pasillo 3, Estante B" value="{{ old('lugar') }}" required>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-center space-x-4 mt-6">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded-md px-4 py-2">
                    Agregar Producto
                </button>
                <a href="{{ route('inventarios.index') }}"
                    class="bg-red-500 hover:bg-red-600 text-white rounded-md px-4 py-2">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
