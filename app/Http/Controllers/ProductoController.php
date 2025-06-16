<?php 

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ProductoController extends Controller
{
    public function index()
    {
        // Devuelve productos con la categoría asociada
        return Producto::with('categoria')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            
            $path = $request->file('imagen')->store('uploads', 'public');
            $validated['imagen'] = basename($path);
        }

        $producto = Producto::create($validated);
        return response()->json($producto->load('categoria'), 201);
    }


    public function show($id)
    {
        return Producto::with('categoria')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|max:2048', 
        ]);

       
        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete('uploads/' . $producto->imagen);
            }
            $nombreImagen = $request->file('imagen')->store('uploads', 'public');
            $producto->imagen = basename($nombreImagen);
        }

        // Actualiza solo si el campo viene
        $producto->nombre = $request->input('nombre', $producto->nombre);
        $producto->descripcion = $request->input('descripcion', $producto->descripcion);
        $producto->precio = $request->input('precio', $producto->precio);
        $producto->stock = $request->input('stock', $producto->stock);
        $producto->categoria_id = $request->input('categoria_id', $producto->categoria_id);

        $producto->save();

        return response()->json($producto->load('categoria'));
    }


    public function destroy($id)
    {
        Producto::destroy($id);
        return response()->json(null, 204);
    }

    // En ProductoController.php
    public function masVendidos()
    {
        $topProductos = DB::table('detalle_ventas')
            ->select('producto_id', DB::raw('SUM(cantidad) as total_vendidos'))
            ->groupBy('producto_id')
            ->orderByDesc('total_vendidos')
            ->take(3)
            ->pluck('producto_id');

        $productos = Producto::with('categoria')
            ->whereIn('id', $topProductos)
            ->get();

        return response()->json($productos);
    }


}
