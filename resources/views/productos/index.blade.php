
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos | Papelería Aylen</title>
    <style>
        body { font-family: Arial; background: #f4f6fa; margin: 35px; color: #222; }
        .contenedor { background: white; padding: 25px; border-radius: 12px; }
        h1 { color: #263b65; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #263b65; color: white; }
        a, button { padding: 8px 12px; border: 0; border-radius: 5px; cursor: pointer; text-decoration: none; }
        .nuevo { background: #263b65; color: white; }
        .editar { background: #e7b84b; color: #222; }
        .eliminar { background: #bd4242; color: white; }
        .mensaje { padding: 10px; background: #d9f3df; margin: 10px 0; }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>Papelería Aylen</h1>
    <h2>Administración de productos</h2>

    @if(session('success'))
        <p class="mensaje">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="mensaje">{{ session('error') }}</p>
    @endif

    <a class="nuevo" href="{{ route('productos.create') }}">+ Nuevo producto</a>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $producto)
                <tr>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    <td>${{ number_format($producto->precio_venta, 2) }}</td>
                    <td>{{ $producto->existencia }}</td>
                    <td>
                        <a class="editar" href="{{ route('productos.edit', $producto) }}">Editar</a>

                        <form action="{{ route('productos.destroy', $producto) }}"
                              method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="eliminar" onclick="return confirm('¿Eliminar producto?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Todavía no hay productos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $productos->links() }}
</div>
</body>
</html>
