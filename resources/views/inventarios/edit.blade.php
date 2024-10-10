<!-- resources/views/inventarios/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Producto</h1>

        <form action="{{ route('inventarios.update', $inventario->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ $inventario->nombre }}" required>
            </div>
            <div class="mb-3">
                <label for="valor_venta" class="form-label">Valor Venta</label>
                <input type="number" step="0.01" name="valor_venta" class="form-control"
                    value="{{ $inventario->valor_venta }}" required>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" name="cantidad" class="form-control" value="{{ $inventario->cantidad }}" required>
            </div>
            <div class="mb-3">
                <label for="lugar" class="form-label">Lugar</label>
                <input type="text" name="lugar" class="form-control" value="{{ $inventario->lugar }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        </form>
    </div>
@endsection
