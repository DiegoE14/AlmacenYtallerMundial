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
        $inventario = Inventario::findOrFail($request->inventario_id);

        // Restar cantidad del inventario
        if ($inventario->cantidad >= $request->cantidad) {
            $inventario->cantidad -= $request->cantidad;
            $inventario->save();

            Facturacion::create([
                'inventario_id' => $request->inventario_id,
                'cantidad' => $request->cantidad,
                'total' => $request->cantidad * $inventario->valor_venta
            ]);

            return redirect()->route('facturaciones.index');
        }

        return back()->withErrors(['error' => 'Cantidad en inventario insuficiente']);
    }
}
