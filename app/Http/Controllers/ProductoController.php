<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['categoria', 'proveedor'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::where('activa', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();

        return view('productos.create', compact(
            'categorias',
            'proveedores'
        ));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'codigo' => 'required|string|max:40|unique:productos,codigo',
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string|max:255',
            'precio_venta' => 'required|numeric|min:0',
            'existencia' => 'required|integer|min:0',
        ]);

        Producto::create($datos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::where('activa', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();

        return view('productos.edit', compact(
            'producto',
            'categorias',
            'proveedores'
        ));
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'codigo' => [
                'required',
                'string',
                'max:40',
                Rule::unique('productos', 'codigo')->ignore($producto->id),
            ],
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string|max:255',
            'precio_venta' => 'required|numeric|min:0',
            'existencia' => 'required|integer|min:0',
        ]);

        $producto->update($datos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->detallesVentas()->exists()) {
            return back()->with(
                'error',
                'No se puede eliminar un producto que tiene ventas registradas.'
            );
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
