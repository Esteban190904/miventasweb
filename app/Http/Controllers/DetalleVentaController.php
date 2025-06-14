<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    public function index()
    {
        return DetalleVenta::with('producto', 'venta')->get(); // Lista todos los detalles con relaciones
    }

    public function show($id)
    {
        return DetalleVenta::with('producto', 'venta')->findOrFail($id);
    }

    public function destroy($id)
    {
        $detalle = DetalleVenta::findOrFail($id);
        $detalle->delete();

        return response()->json(['mensaje' => 'Detalle de venta eliminado correctamente']);
    }
}
