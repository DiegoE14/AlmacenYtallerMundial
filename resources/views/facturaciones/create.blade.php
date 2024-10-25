@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8 max-w-6xl">
        <h1 class="text-4xl font-bold mb-6 text-center text-gray-800">Crear Nueva Factura</h1>

        <form id="factura-form" action="{{ route('facturaciones.store') }}" method="POST"
            class="bg-white shadow-md rounded-lg p-6">
            @csrf

            <!-- Contenedor de productos -->
            <div id="productos-container" class="mb-6">
                <div class="producto-row card shadow-sm p-4 mb-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center space-x-4">
                        <!-- Producto -->
                        <div class="flex-1">
                            <label for="inventario_id_0" class="form-label font-semibold">Producto</label>
                            <select id="inventario_id_0" name="inventario_id[]"
                                class="form-control inventario-select border rounded-md shadow-sm" required>
                                <option value="" disabled selected>Seleccione un producto</option>
                                @foreach ($inventarios as $inventario)
                                    <option value="{{ $inventario->id }}" data-precio="{{ $inventario->valor_venta }}">
                                        {{ $inventario->nombre }} (Disponible: {{ $inventario->cantidad }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cantidad (con etiqueta arriba del input) -->
                        <div class="flex-1 w-24">
                            <label for="cantidad_0" class="form-label font-semibold">Cantidad</label>
                            <input type="number" id="cantidad_0" name="cantidad[]"
                                class="form-control cantidad-input border rounded-md shadow-sm w-full" required
                                min="1" disabled>
                        </div>

                        <!-- Valor de Venta -->
                        <div class="flex-1">
                            <label for="valor_venta_0" class="form-label font-semibold">Valor de Venta</label>
                            <input type="text" id="valor_venta_0"
                                class="form-control valor-venta-input border rounded-md shadow-sm" readonly>
                        </div>

                        <!-- Descuento -->
                        <div class="flex-1">
                            <label for="descuento_0" class="form-label font-semibold">Descuento</label>
                            <select id="descuento_0" name="descuento[]"
                                class="form-control descuento-select border rounded-md shadow-sm" disabled>
                                <option value="0">Sin Descuento</option>
                                <option value="0.10">10% Descuento</option>
                            </select>
                        </div>

                        <!-- Valor Final -->
                        <div class="flex-1">
                            <label for="valor_final_0" class="form-label font-semibold">Valor Final</label>
                            <input type="text" id="valor_final_0"
                                class="form-control valor-final-input border rounded-md shadow-sm" readonly>
                        </div>

                        <!-- Botón para quitar fila -->
                        <div>
                            <button type="button"
                                class="btn btn-danger quitar-fila bg-red-500 hover:bg-red-600 text-white rounded-md px-3">-
                                Quitar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total -->
            <div class="card shadow-sm p-4 mb-4 bg-gray-100 rounded-lg border border-gray-200">
                <h2 class="text-xl font-semibold mb-3">Total a Pagar</h2>
                <input type="text" id="total" class="form-control bg-gray-200 border rounded-md shadow-sm" readonly>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-between">
                <button type="button" id="agregar-fila"
                    class="btn btn-primary bg-blue-500 hover:bg-blue-600 text-white rounded-md px-4 py-2">+ Añadir
                    Producto</button>
                <button type="submit"
                    class="btn btn-success bg-green-500 hover:bg-green-600 text-white rounded-md px-4 py-2">Crear
                    Factura</button>
            </div>
        </form>
    </div>

    <!-- Script para manejar la adición y eliminación de filas dinámicas y actualizar valores -->
    <script>
        $(document).ready(function() {
            // Inicializar Select2 en el select de productos
            $('.inventario-select').select2({
                placeholder: "Seleccione un producto",
                allowClear: true
            });

            const contenedorProductos = document.getElementById('productos-container');
            const botonAgregarFila = document.getElementById('agregar-fila');
            const totalInput = document.getElementById('total');
            const facturaForm = document.getElementById('factura-form');

            // Función para calcular el total de todos los valores finales
            function calcularTotal() {
                let total = 0;
                const filas = document.querySelectorAll('.producto-row');
                filas.forEach(fila => {
                    const valorFinalInput = fila.querySelector('.valor-final-input');
                    const valorFinal = parseFloat(valorFinalInput.value) || 0;
                    total += valorFinal; // Sumar el valor final de cada fila
                });
                totalInput.value = total.toFixed(2); // Actualizar el campo total
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
                        if (seleccionados.includes(option.val())) {
                            option.prop('disabled', true);
                        } else {
                            option.prop('disabled', false);
                        }
                    });
                    select.select2(); // Re-inicializar select2 para aplicar cambios
                });
            }

            // Función para actualizar los valores en la fila
            function actualizarValores() {
                const fila = this.closest('.producto-row'); // Obtener la fila actual
                const inventarioSelect = fila.querySelector('.inventario-select');
                const cantidadInput = fila.querySelector('.cantidad-input');
                const valorVentaInput = fila.querySelector('.valor-venta-input');
                const descuentoSelect = fila.querySelector('.descuento-select');
                const valorFinalInput = fila.querySelector('.valor-final-input');

                const productoSeleccionado = inventarioSelect.options[inventarioSelect.selectedIndex];
                const precio = parseFloat(productoSeleccionado.getAttribute('data-precio')) || 0;
                const cantidad = parseInt(cantidadInput.value) || 0;
                const descuento = parseFloat(descuentoSelect.value) || 0;

                // Calcular el valor de venta (precio unitario * cantidad)
                const valorVenta = precio * cantidad;
                valorVentaInput.value = valorVenta.toFixed(2);

                // Aplicar el descuento
                const valorFinal = valorVenta - (valorVenta * descuento);
                valorFinalInput.value = valorFinal.toFixed(2);

                calcularTotal(); // Recalcular el total al actualizar valores
            }

            // Función para crear una nueva fila de producto
            function crearFilaProducto() {
                const filaHtml = `
                    <div class="producto-row card shadow-sm p-4 mb-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <label for="inventario_id_new" class="form-label font-semibold">Producto</label>
                                <select id="inventario_id_new" name="inventario_id[]" class="form-control inventario-select border rounded-md shadow-sm" required>
                                    <option value="" disabled selected>Seleccione un producto</option>
                                    @foreach ($inventarios as $inventario)
                                        <option value="{{ $inventario->id }}" data-precio="{{ $inventario->valor_venta }}">
                                            {{ $inventario->nombre }} (Disponible: {{ $inventario->cantidad }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1 w-24"> <!-- Cambié w-20 a w-24 para hacer el input más estrecho -->
                                <label for="cantidad_new" class="form-label font-semibold">Cantidad</label>
                                <input type="number" id="cantidad_new" name="cantidad[]" class="form-control cantidad-input border rounded-md shadow-sm w-full" required min="1" disabled>
                            </div>

                            <div class="flex-1">
                                <label for="valor_venta_new" class="form-label font-semibold">Valor de Venta</label>
                                <input type="text" id="valor_venta_new" class="form-control valor-venta-input border rounded-md shadow-sm" readonly>
                            </div>

                            <div class="flex-1">
                                <label for="descuento_new" class="form-label font-semibold">Descuento</label>
                                <select id="descuento_new" name="descuento[]" class="form-control descuento-select border rounded-md shadow-sm" disabled>
                                    <option value="0">Sin Descuento</option>
                                    <option value="0.10">10% Descuento</option>
                                </select>
                            </div>

                            <div class="flex-1">
                                <label for="valor_final_new" class="form-label font-semibold">Valor Final</label>
                                <input type="text" id="valor_final_new" class="form-control valor-final-input border rounded-md shadow-sm" readonly>
                            </div>

                            <div>
                                <button type="button" class="btn btn-danger quitar-fila bg-red-500 hover:bg-red-600 text-white rounded-md px-3">- Quitar</button>
                            </div>
                        </div>
                    </div>
                `;
                contenedorProductos.insertAdjacentHTML('beforeend', filaHtml);
                const nuevaFila = contenedorProductos.lastElementChild;
                agregarEventosFila(nuevaFila); // Agregar eventos a la nueva fila
                actualizarSelectOptions(); // Actualizar opciones después de agregar fila
            }

            // Función para agregar eventos a una fila
            function agregarEventosFila(fila) {
                const inventarioSelect = fila.querySelector('.inventario-select');
                const cantidadInput = fila.querySelector('.cantidad-input');
                const valorVentaInput = fila.querySelector('.valor-venta-input');
                const descuentoSelect = fila.querySelector('.descuento-select');
                const valorFinalInput = fila.querySelector('.valor-final-input');
                const quitarFilaButton = fila.querySelector('.quitar-fila');

                // Evento para manejar el cambio de selección de producto
                $(inventarioSelect).on('select2:select', function(e) {
                    const productoSeleccionado = e.params.data; // Obtenemos el producto seleccionado
                    const precio = parseFloat($(this).find(`option[value="${productoSeleccionado.id}"]`)
                        .data('precio'));

                    cantidadInput.value = 1; // Establecer cantidad en 1
                    cantidadInput.disabled = false; // Habilitar cantidad
                    descuentoSelect.disabled = false; // Habilitar descuento
                    actualizarValores.call(inventarioSelect); // Llamar a actualizar valores
                    actualizarSelectOptions(); // Actualizar opciones después de seleccionar un producto
                });

                // Evento para manejar la cantidad
                cantidadInput.addEventListener('input', function() {
                    if (!this.disabled) {
                        actualizarValores.call(inventarioSelect);
                    }
                });

                // Evento para manejar el cambio de descuento
                descuentoSelect.addEventListener('change', function() {
                    if (!this.disabled) {
                        actualizarValores.call(inventarioSelect);
                    }
                });

                // Evento para quitar la fila
                quitarFilaButton.addEventListener('click', function() {
                    fila.remove();
                    calcularTotal(); // Recalcular total al quitar fila
                    actualizarSelectOptions(); // Actualizar opciones después de quitar fila
                });

                // Evento para reiniciar campos al cerrar el select2
                $(inventarioSelect).on('select2:clearing', function() {
                    cantidadInput.disabled = true; // Deshabilitar cantidad
                    descuentoSelect.disabled = true; // Deshabilitar descuento
                    cantidadInput.value = ''; // Limpiar cantidad
                    valorFinalInput.value = ''; // Limpiar valor final
                    valorVentaInput.value = ''; // Limpiar valor venta
                    calcularTotal(); // Recalcular total al quitar fila
                    actualizarSelectOptions(); // Actualizar opciones al limpiar
                });
            }

            // Inicializar con la primera fila
            agregarEventosFila(contenedorProductos.firstElementChild);

            // Evento para agregar nueva fila de productos
            botonAgregarFila.addEventListener('click', crearFilaProducto);

            // Evento para validar filas antes de enviar el formulario
            facturaForm.addEventListener('submit', function(event) {
                const filas = document.querySelectorAll('.producto-row');
                let filasVacias = false;

                filas.forEach(fila => {
                    const inventarioSelect = fila.querySelector('.inventario-select');
                    const cantidadInput = fila.querySelector('.cantidad-input');

                    // Comprobar si el producto o cantidad están vacíos
                    if (!inventarioSelect.value || !cantidadInput.value) {
                        filasVacias = true;
                    }
                });

                // Si hay filas vacías, mostrar un mensaje y prevenir el envío
                if (filasVacias) {
                    event.preventDefault(); // Evitar el envío del formulario
                    alert(
                        "Por favor, complete todas las filas o elimine las vacías antes de continuar."
                    ); // Mensaje de alerta
                }
            });
        });
    </script>
@endsection
