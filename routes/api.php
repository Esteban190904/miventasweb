<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\GeminiChatController;


// Rutas públicas (acceso sin autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Puedes permitir a cualquier usuario ver productos (catálogo público)
  Route::get('/productos/estrella', [ProductoController::class, 'masVendidos']);

Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);

// Rutas protegidas (requieren autenticación con token Bearer)
Route::middleware('auth:sanctum')->group(function () {

    // Cierre de sesión (logout)
    Route::post('/logout', [AuthController::class, 'logout']);

    // Usuarios (solo administradores, normalmente)
    Route::apiResource('usuarios', UsuarioController::class);

    // Productos (crear, actualizar, eliminar - solo vendedores/admins)
    Route::post('/productos', [ProductoController::class, 'store']);
    Route::put('/productos/{id}', [ProductoController::class, 'update']);
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);

    // Ventas
    Route::apiResource('ventas', VentaController::class);

    // Detalle de ventas
    Route::apiResource('detalle-ventas', DetalleVentaController::class);

    // Aquí puedes agregar más rutas protegidas...
});

// Rutas públicas de carrito
Route::post('/carrito/add', [CarritoController::class, 'add']);
Route::get('/carrito', [CarritoController::class, 'index']);
Route::post('/carrito/remove', [CarritoController::class, 'remove']);
Route::post('/carrito/clear', [CarritoController::class, 'clear']);
Route::post('/carrito/checkout', [CarritoController::class, 'checkout']);
Route::get('/carrito/checkout', [CarritoController::class, 'checkout']);



// Ruta de prueba (opcional)
Route::get('/prueba', function () {
    return ['mensaje' => 'API funcionando'];
});

Route::apiResource('categorias', CategoriaController::class);

Route::apiResource('clientes', ClienteController::class);
Route::post('/login-cliente', [ClienteController::class, 'login']);


Route::post('/chatbot', [ChatbotController::class, 'responder']);


Route::post('/chatbot', [GeminiChatController::class, 'responder_gemini']);


