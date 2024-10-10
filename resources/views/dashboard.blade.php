<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg">Gestión de Módulos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Módulo de Inventario -->
                        <div class="p-4 bg-blue-100 rounded-lg shadow">
                            <h4 class="font-bold">Inventario</h4>
                            <p>Administra los productos en tu inventario.</p>
                            <a href="{{ route('inventarios.index') }}"
                                class="mt-2 inline-block text-blue-600 hover:underline">Ir a Inventario</a>
                        </div>

                        <!-- Módulo de Facturación -->
                        <div class="p-4 bg-green-100 rounded-lg shadow">
                            <h4 class="font-bold">Facturación</h4>
                            <p>Gestiona las ventas y las facturas generadas.</p>
                            <a href="{{ route('facturaciones.index') }}"
                                class="mt-2 inline-block text-green-600 hover:underline">Ir a Facturación</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
