<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    // Listar todas las ventas
   public function index()
    {
        return Venta::with(['usuario', 'detalleVentas.producto'])->get();
    }



    // Registrar una nueva venta
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'fecha' => 'required|date',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $detalles_validos = [];

            foreach ($request->detalles as $detalle) {
                // Obtén el producto desde la BD
                $producto = \App\Models\Producto::findOrFail($detalle['producto_id']);

                // Verifica stock suficiente
                if ($producto->stock < $detalle['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}");
                }

                // Calcula el subtotal (precio actual de la BD * cantidad)
                $subtotal = $producto->precio * $detalle['cantidad'];
                $total += $subtotal;

                // Almacena el detalle listo para registrar
                $detalles_validos[] = [
                    'producto_id' => $producto->id,
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $producto->precio // Cambiado aquí
                ];
            }

            // Crea la venta
            $venta = Venta::create([
                'usuario_id' => $request->usuario_id,
                'fecha' => $request->fecha,
                'total' => $total
            ]);

            // Registra cada detalle y descuenta stock
            foreach ($detalles_validos as $detalle) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $detalle['precio_unitario'] * $detalle['cantidad'] // <-- AGREGA ESTO
                ]);

                // Descuenta stock
                $producto = \App\Models\Producto::find($detalle['producto_id']);
                $producto->stock -= $detalle['cantidad'];
                $producto->save();
            }


            DB::commit();
            return response()->json($venta->load('detalleVentas'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al registrar la venta: ' . $e->getMessage()], 500);
        }
    }

    // Mostrar una venta específica
    public function show($id)
    {
        // Asegúrate de incluir la relación producto en cada detalle
        return Venta::with(['usuario', 'detalleVentas.producto'])->findOrFail($id);
    }


    // Eliminar una venta
    public function destroy($id)
    {
        Venta::destroy($id);
        return response()->json(null, 204);
    }
}
