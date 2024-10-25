<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Facturacion;

class FacturacionController extends Controller
{
    public function index()
    {
        $facturaciones = Facturacion::with('inventario')->get();
        return view('facturaciones.index', compact('facturaciones'));
    }

    public function create()
    {
        $inventarios = Inventario::all();
        return view('facturaciones.create', compact('inventarios'));
    }

    public function store(Request $request)
    {
        // Validar que se reciba al menos un producto
        $request->validate([
            'productos' => 'required|array',
            'productos.*.inventario_id' => 'required|exists:inventarios,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        // Iterar sobre los productos facturados
        foreach ($request->productos as $producto) {
            $inventario = Inventario::findOrFail($producto['inventario_id']);

            // Verificar si hay suficiente cantidad en inventario
            if ($inventario->cantidad >= $producto['cantidad']) {
                // Restar la cantidad del inventario
                $inventario->cantidad -= $producto['cantidad'];
                $inventario->save();

                // Crear la facturación
                Facturacion::create([
                    'inventario_id' => $producto['inventario_id'],
                    'cantidad' => $producto['cantidad'],
                    'total' => $producto['cantidad'] * $inventario->valor_venta,
                ]);
            } else {
                return back()->withErrors(['error' => "Cantidad insuficiente para el producto: {$inventario->nombre}"]);
            }
        }

        return redirect()->route('facturaciones.index')->with('success', 'Factura creada exitosamente.');
    }
}
