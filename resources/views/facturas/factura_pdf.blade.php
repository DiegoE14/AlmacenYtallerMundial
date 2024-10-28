<!-- resources/views/facturas/factura_pdf.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Factura #{{ $numeroFactura }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .factura-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Factura</h1>
    </div>

    <div class="factura-info">
        <p><strong>Número de Factura:</strong> {{ $numeroFactura }}</p>
        <p><strong>Fecha:</strong> {{ $fecha->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Descuento</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->inventario->nombre }}</td>
                    <td>{{ $producto->cantidad }}</td>
                    <td>${{ number_format($producto->valor_final, 2) }}</td>
                    <td>{{ $producto->descuento ? '10%' : '0%' }}</td>
                    <td>${{ number_format($producto->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total: ${{ number_format($totalFactura, 2) }}</p>
    </div>
</body>

</html>
