<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    // Mostrar historial de ventas
    public function index()
    {
        $ventas = Venta::with(['usuario', 'cliente', 'detalles.producto'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('ventas.index', compact('ventas'));
    }

    // Mostrar formulario de nueva venta
    public function create()
    {
        $productos = Producto::where('activo', true)
            ->where('existencia', '>', 0)
            ->orderBy('nombre')
            ->get();

        return view('ventas.create', compact('productos'));
    }

    // Registrar una venta
    public function store(Request $request)
    {
        $datos = $request->validate([
            'metodo_pago' => 'required|in:EFECTIVO,TARJETA,TRANSFERENCIA,OTRO',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|integer|distinct|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        // Usuario de la sesión o primer usuario registrado.
        // El segundo caso permite realizar pruebas locales sin login.
        $usuarioId = auth()->id() ?? User::query()->value('id');

        if (!$usuarioId) {
            throw ValidationException::withMessages([
                'productos' => 'Debe existir un usuario registrado para realizar ventas.',
            ]);
        }

        $venta = DB::transaction(function () use ($datos, $usuarioId) {

            $subtotal = 0;
            $detalles = [];

            foreach ($datos['productos'] as $item) {
                $producto = Producto::whereKey($item['id'])
                    ->lockForUpdate()
                    ->first();

                if (!$producto || !$producto->activo) {
                    throw ValidationException::withMessages([
                        'productos' => 'Uno de los productos no está disponible.',
                    ]);
                }

                $cantidad = (int) $item['cantidad'];

                if ($producto->existencia < $cantidad) {
                    throw ValidationException::withMessages([
                        'productos' => 'Existencia insuficiente para: '
                            . $producto->nombre,
                    ]);
                }

                $precio = (float) $producto->precio_venta;
                $importe = round($precio * $cantidad, 2);

                $subtotal += $importe;

                $detalles[] = [
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'importe' => $importe,
                ];
            }

            $subtotal = round($subtotal, 2);
            $descuento = 0;
            $total = $subtotal - $descuento;

            $venta = Venta::create([
                'user_id' => $usuarioId,
                'cliente_id' => null,
                'fecha_venta' => now(),
                'metodo_pago' => $datos['metodo_pago'],
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'total' => $total,
                'estado' => 'COMPLETADA',
            ]);

            foreach ($detalles as $detalle) {
                $venta->detalles()->create($detalle);

                Producto::whereKey($detalle['producto_id'])
                    ->decrement('existencia', $detalle['cantidad']);
            }

            return $venta;
        });

        return redirect()->route('ventas.index')
            ->with('success', 'Venta registrada correctamente. Folio: '
                . $venta->id);
    }
}
