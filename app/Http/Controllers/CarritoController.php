<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CarritoController extends Controller
{
    // Obtener el carrito del usuario autenticado 
    public function index(Request $request)
    {
        $usuario_id = 1; 
        $carrito = Carrito::where('usuario_id', $usuario_id)
            ->where('completado', false)
            ->with('productos')
            ->first();

        if (!$carrito) {
            return response()->json(['productos' => []]);
        }

        return response()->json($carrito->productos);
    }

    // Agregar producto
    public function add(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);
        $usuario_id = 1; 

        $carrito = Carrito::firstOrCreate(
            ['usuario_id' => $usuario_id, 'completado' => false]
        );

        // Revisa si ya existe el producto en el carrito
        if ($carrito->productos()->where('producto_id', $request->producto_id)->exists()) {
            $carrito->productos()->updateExistingPivot($request->producto_id, [
                'cantidad' => DB::raw('cantidad + ' . $request->cantidad)
            ]);
        } else {
            $carrito->productos()->attach($request->producto_id, [
                'cantidad' => $request->cantidad
            ]);
        }

        return response()->json(['mensaje' => 'Producto agregado']);
    }

   
    public function checkout(Request $request)
    {
        $usuario_id = 1; 

        // Busca el carrito activo del usuario
        $carrito = \App\Models\Carrito::where('usuario_id', $usuario_id)
                                    ->where('completado', false)
                                    ->with('productos')
                                    ->first();

        if (!$carrito || $carrito->productos->isEmpty()) {
            return response()->json(['error' => 'El carrito está vacío'], 400);
        }

        // Marcar el carrito como completado
        $carrito->completado = true;
        $carrito->save();

        // Calcular total
        $total = 0;
        foreach ($carrito->productos as $item) {
            $total += $item->pivot->cantidad * $item->precio;
        }

        // Generar PDF con los productos del carrito
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('boleta', [
            'carrito' => $carrito->productos,
            'nombre_cliente' => 'Cliente', 
            'total' => $total
        ]);

       

        // Descargar PDF
        return $pdf->download('mini-boleta.pdf');
    }


    public function remove(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
        ]);

        $usuario_id = 1;

        // Busca el carrito no completado del usuario
        $carrito = \App\Models\Carrito::where('usuario_id', $usuario_id)
                                    ->where('completado', false)
                                    ->first();

        if (!$carrito) {
            return response()->json(['error' => 'Carrito no encontrado'], 404);
        }

        // Elimina el producto del carrito
        $carrito->productos()->detach($request->producto_id);

        return response()->json(['mensaje' => 'Producto eliminado del carrito']);
    }

    public function clear(Request $request)
    {
        $usuario_id = 1; 

        $carrito = \App\Models\Carrito::where('usuario_id', $usuario_id)
                                    ->where('completado', false)
                                    ->first();

        if (!$carrito) {
            return response()->json(['error' => 'Carrito no encontrado'], 404);
        }

        
        $carrito->productos()->detach();

        return response()->json(['mensaje' => 'Carrito vaciado']);
    }


}
