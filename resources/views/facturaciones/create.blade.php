@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8 max-w-6xl">
        <h1 class="text-4xl font-bold mb-6 text-center text-gray-800">Crear Nueva Factura</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="factura-form" action="{{ route('facturaciones.store') }}" method="POST"
            class="bg-white shadow-md rounded-lg p-6">
            @csrf

            <!-- Contenedor de productos -->
            <div id="productos-container" class="mb-6">
                <!-- La primera fila se insertará mediante JavaScript -->
            </div>

            <!-- Total -->
            <div class="card shadow-sm p-4 mb-4 bg-gray-100 rounded-lg border border-gray-200">
                <h2 class="text-xl font-semibold mb-3">Total a Pagar</h2>
                <input type="text" id="total" class="form-control bg-gray-200 border rounded-md shadow-sm" readonly>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-between">
                <button type="button" id="agregar-fila"
                    class="btn btn-primary bg-blue-500 hover:bg-blue-600 text-white rounded-md px-4 py-2">
                    + Añadir Producto
                </button>
                <button type="submit"
                    class="btn btn-success bg-green-500 hover:bg-green-600 text-white rounded-md px-4 py-2">
                    Crear Factura
                </button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            const contenedorProductos = document.getElementById('productos-container');
            const botonAgregarFila = document.getElementById('agregar-fila');
            const totalInput = document.getElementById('total');
            const facturaForm = document.getElementById('factura-form');

            // Función para calcular el total
            function calcularTotal() {
                let total = 0;
                const filas = document.querySelectorAll('.producto-row');
                filas.forEach(fila => {
                    const valorFinalInput = fila.querySelector('.valor-final-input');
                    const valorFinal = parseFloat(valorFinalInput.value) || 0;
                    total += valorFinal;
                });
                totalInput.value = total.toFixed(2);
            }

            // Función para actualizar las opciones del select2
            function actualizarSelectOptions() {
                const seleccionados = [];
                document.querySelectorAll('.inventario-select').forEach(select => {
                    const value = select.value;
                    if (value) {
                        seleccionados.push(value);
                    }
                });

                $('.inventario-select').each(function() {
                    const select = $(this);
                    const options = select.find('option');
                    options.each(function() {
                        const option = $(this);
                        if (seleccionados.includes(option.val()) && option.val() !== select.val()) {
                            option.prop('disabled', true);
                        } else {
                            option.prop('disabled', false);
                        }
                    });
                    select.select2();
                });
            }

            // Función para actualizar los valores en la fila
            function actualizarValores() {
                const fila = this.closest('.producto-row');
                const inventarioSelect = fila.querySelector('.inventario-select');
                const cantidadInput = fila.querySelector('.cantidad-input');
                const valorVentaInput = fila.querySelector('.valor-venta-input');
                const descuentoSelect = fila.querySelector('.descuento-select');
                const valorFinalInput = fila.querySelector('.valor-final-input');

                const productoSeleccionado = inventarioSelect.options[inventarioSelect.selectedIndex];
                const precio = parseFloat(productoSeleccionado.getAttribute('data-precio')) || 0;
                const cantidad = parseInt(cantidadInput.value) || 0;
                const descuento = parseFloat(descuentoSelect.value) || 0;

                const valorVenta = precio * cantidad;
                valorVentaInput.value = valorVenta.toFixed(2);

                const valorFinal = valorVenta - (valorVenta * descuento);
                valorFinalInput.value = valorFinal.toFixed(2);

                calcularTotal();
            }

            // Función para crear una nueva fila de producto
            function crearFilaProducto() {
                const filaHtml = `
                    <div class="producto-row card shadow-sm p-4 mb-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <label class="form-label font-semibold">Producto</label>
                                <select name="inventario_id[]" class="form-control inventario-select border rounded-md shadow-sm" required>
                                    <option value="" disabled selected>Seleccione un producto</option>
                                    @foreach ($inventarios as $inventario)
                                        <option value="{{ $inventario->id }}" data-precio="{{ $inventario->valor_venta }}">
                                            {{ $inventario->nombre }} (Disponible: {{ $inventario->cantidad }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1 w-24">
                                <label class="form-label font-semibold">Cantidad</label>
                                <input type="number" name="cantidad[]" class="form-control cantidad-input border rounded-md shadow-sm w-full" 
                                    required min="1" disabled>
                            </div>

                            <div class="flex-1">
                                <label class="form-label font-semibold">Valor de Venta</label>
                                <input type="text" class="form-control valor-venta-input border rounded-md shadow-sm" readonly>
                            </div>

                            <div class="flex-1">
                                <label class="form-label font-semibold">Descuento</label>
                                <select name="descuento[]" class="form-control descuento-select border rounded-md shadow-sm" disabled>
                                    <option value="0">Sin Descuento</option>
                                    <option value="0.10">10% Descuento</option>
                                </select>
                            </div>

                            <div class="flex-1">
                                <label class="form-label font-semibold">Valor Final</label>
                                <input type="text" name="valor_final[]" class="form-control valor-final-input border rounded-md shadow-sm" readonly>
                            </div>

                            <div>
                                <button type="button" class="btn btn-danger quitar-fila bg-red-500 hover:bg-red-600 text-white rounded-md px-3">
                                    - Quitar
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                contenedorProductos.insertAdjacentHTML('beforeend', filaHtml);
                const nuevaFila = contenedorProductos.lastElementChild;
                agregarEventosFila(nuevaFila);
                actualizarSelectOptions();
            }

            // Función para agregar eventos a una fila
            function agregarEventosFila(fila) {
                const inventarioSelect = fila.querySelector('.inventario-select');
                const cantidadInput = fila.querySelector('.cantidad-input');
                const descuentoSelect = fila.querySelector('.descuento-select');
                const quitarFilaButton = fila.querySelector('.quitar-fila');

                // Inicializar Select2
                $(inventarioSelect).select2({
                    placeholder: "Seleccione un producto",
                    allowClear: true
                });

                // Evento para cambio de producto
                $(inventarioSelect).on('select2:select', function(e) {
                    const productoSeleccionado = e.params.data;
                    const precio = parseFloat($(this).find(`option[value="${productoSeleccionado.id}"]`)
                        .data('precio'));

                    cantidadInput.value = 1;
                    cantidadInput.disabled = false;
                    descuentoSelect.disabled = false;
                    actualizarValores.call(inventarioSelect);
                    actualizarSelectOptions();
                });

                // Evento para cantidad
                cantidadInput.addEventListener('input', function() {
                    if (!this.disabled) {
                        actualizarValores.call(inventarioSelect);
                    }
                });

                // Evento para descuento
                descuentoSelect.addEventListener('change', function() {
                    if (!this.disabled) {
                        actualizarValores.call(inventarioSelect);
                    }
                });

                // Evento para quitar fila
                quitarFilaButton.addEventListener('click', function() {
                    fila.remove();
                    calcularTotal();
                    actualizarSelectOptions();
                });

                // Evento para limpiar selección
                $(inventarioSelect).on('select2:clearing', function() {
                    cantidadInput.disabled = true;
                    descuentoSelect.disabled = true;
                    cantidadInput.value = '';
                    fila.querySelector('.valor-venta-input').value = '';
                    fila.querySelector('.valor-final-input').value = '';
                    calcularTotal();
                    actualizarSelectOptions();
                });
            }

            // Crear primera fila al cargar
            crearFilaProducto();

            // Evento para agregar nueva fila
            botonAgregarFila.addEventListener('click', crearFilaProducto);

            // Validación del formulario
            facturaForm.addEventListener('submit', function(event) {
                const filas = document.querySelectorAll('.producto-row');
                let filasVacias = false;

                filas.forEach(fila => {
                    const inventarioSelect = fila.querySelector('.inventario-select');
                    const cantidadInput = fila.querySelector('.cantidad-input');

                    if (!inventarioSelect.value || !cantidadInput.value) {
                        filasVacias = true;
                    }
                });

                if (filasVacias) {
                    event.preventDefault();
                    alert("Por favor, complete todas las filas o elimine las vacías antes de continuar.");
                }
            });
        });
    </script>
@endsection
