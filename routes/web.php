<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarritoController;


Route::get('/', function () {
    return redirect('/public.html');
});



Route::get('/carrito/boleta', [CarritoController::class, 'generarBoleta']);


// Carrito de compras
Route::get('/carrito', [CarritoController::class, 'index']);
Route::post('/carrito/add', [CarritoController::class, 'add']);
Route::post('/carrito/remove', [CarritoController::class, 'remove']);
Route::post('/carrito/clear', [CarritoController::class, 'clear']);

