<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de ventas | Papelería Aylen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f5fa;
            margin: 0;
            padding: 40px;
        }
        .contenedor {
            background: white;
            padding: 30px;
            border-radius: 12px;
        }
        h1 { color: #263d68; }
        .boton {
            display: inline-block;
            background: #263d68;
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #263d68;
            color: white;
            padding: 14px;
            text-align: left;
        }
        td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
        }
        .mensaje {
            background: #d7f4df;
            padding: 12px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>Papelería Aylen</h1>
    <h2>Historial de ventas</h2>

    @if(session('success'))
        <div class="mensaje">{{ session('success') }}</div>
    @endif

    <a class="boton" href="{{ route('ventas.create') }}">
        + Nueva venta
    </a>

    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Método de pago</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>{{ $venta->fecha_venta }}</td>
                    <td>{{ $venta->usuario?->name ?? 'Sin usuario' }}</td>
                    <td>{{ $venta->metodo_pago }}</td>
                    <td>${{ number_format($venta->total, 2) }}</td>
                    <td>{{ $venta->estado }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Todavía no hay ventas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $ventas->links() }}
</div>
</body>
</html>
