<!-- resources/views/facturaciones/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Nueva Factura</h1>

        <form action="{{ route('facturaciones.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="inventario_id" class="form-label">Producto</label>
                <select name="inventario_id" class="form-control" required>
                    @foreach ($inventarios as $inventario)
                        <option value="{{ $inventario->id }}">{{ $inventario->nombre }} (Cantidad disponible:
                            {{ $inventario->cantidad }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" name="cantidad" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Factura</button>
        </form>
    </div>
@endsection
