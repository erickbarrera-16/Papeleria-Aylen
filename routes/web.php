<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;

Route::get('/', function () {
    return redirect()->route('productos.index');
});

Route::resource('productos', ProductoController::class)
    ->except(['show']);

Route::resource('ventas', VentaController::class)
    ->only(['index', 'create', 'store']);