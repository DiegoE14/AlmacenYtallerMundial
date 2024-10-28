<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Facturacion;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class FacturacionController extends Controller
{
    public function index()
    {
        // Obtener todas las facturas y agrupar por numero_factura
        $facturaciones = Facturacion::with('inventario')
            ->select('numero_factura', DB::raw('SUM(total) as total'), DB::raw('MIN(created_at) as created_at'))
            ->groupBy('numero_factura')
            ->get();

        return view('facturaciones.index', compact('facturaciones'));
    }

    public function create()
    {
        $inventarios = Inventario::all();
        return view('facturaciones.create', compact('inventarios'));
    }

    public function store(Request $request)
    {
        // Validar todos los campos necesarios
        $request->validate([
            'inventario_id' => 'required|array',
            'cantidad' => 'required|array',
            'valor_final' => 'required|array',
            'descuento' => 'required|array',
            'inventario_id.*' => 'required|exists:inventarios,id',
            'cantidad.*' => 'required|integer|min:1',
            'valor_final.*' => 'required|numeric|min:0',
            'descuento.*' => 'required|in:0,0.10',
        ]);

        try {
            DB::beginTransaction();

            $ultimoNumeroFactura = Facturacion::max('numero_factura') ?? 0;
            $nuevoNumeroFactura = $ultimoNumeroFactura + 1;

            $facturaProductos = [];
            $totalFactura = 0;

            // Iterar sobre los productos facturados
            foreach ($request->inventario_id as $index => $inventarioId) {
                $cantidad = $request->cantidad[$index];
                $valorFinal = $request->valor_final[$index];
                $descuento = $request->descuento[$index];

                // Calcular el total con descuento
                $totalProducto = $valorFinal;
                if ($descuento == '0.10') {
                    $totalProducto = $valorFinal * 0.9; // Aplicar 10% de descuento
                }

                $totalFactura += $totalProducto;

                $inventario = Inventario::findOrFail($inventarioId);
                $inventario->cantidad -= $cantidad;
                $inventario->save();

                $facturaProducto = Facturacion::create([
                    'numero_factura' => $nuevoNumeroFactura,
                    'inventario_id' => $inventarioId,
                    'cantidad' => $cantidad,
                    'valor_final' => $valorFinal,
                    'descuento' => $descuento == '0.10',
                    'total' => $totalProducto,
                ]);

                // Cargar la relación inventario para tener los datos completos del producto
                $facturaProducto->load('inventario');
                $facturaProductos[] = $facturaProducto;
            }

            // Generar el PDF
            $pdf = PDF::loadView('facturas.factura_pdf', [
                'numeroFactura' => $nuevoNumeroFactura,
                'productos' => $facturaProductos,
                'totalFactura' => $totalFactura,
                'fecha' => now(),
            ]);

            // Guardar el PDF
            $pdfPath = 'public/facturas/factura_' . $nuevoNumeroFactura . '.pdf';
            Storage::put($pdfPath, $pdf->output());

            // Redirección con mensaje de éxito

            DB::commit();

            return redirect()
                ->route('facturaciones.index')
                ->with('success', 'Factura creada exitosamente con número: ' . $nuevoNumeroFactura);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al generar la factura: ' . $e->getMessage()]);
        }
    }

    public function show($numeroFactura)
    {
        // Obtener la factura específica
        $factura = Facturacion::where('numero_factura', $numeroFactura)->firstOrFail();

        // Obtener todos los productos relacionados con esta factura
        $productos = Facturacion::where('numero_factura', $numeroFactura)->get();

        // Calcular el total de la factura sumando el total de los productos
        $totalFactura = $productos->sum('total');

        return view('facturaciones.show', compact('factura', 'productos', 'totalFactura'));
    }

    public function showPdf($numeroFactura)
    {
        try {
            // Obtener todos los productos de la factura con sus relaciones
            $productos = Facturacion::with('inventario')
                ->where('numero_factura', $numeroFactura)
                ->get();

            if ($productos->isEmpty()) {
                return redirect()->route('facturaciones.index')
                    ->with('error', 'Factura no encontrada.');
            }

            $totalFactura = $productos->sum('total');

            $pdf = PDF::loadView('facturas.factura_pdf', [
                'numeroFactura' => $numeroFactura,
                'productos' => $productos,
                'totalFactura' => $totalFactura,
                'fecha' => $productos->first()->created_at,
            ]);

            // Retornar el PDF para descarga o visualización
            return $pdf->stream("factura_{$numeroFactura}.pdf");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}
