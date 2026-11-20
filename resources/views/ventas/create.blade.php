<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva venta | Papelería Aylen</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f3f5fa;
            margin: 0;
            padding: 35px;
            color: #222;
        }

        .contenedor {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
        }

        h1 {
            color: #263d68;
        }

        .boton {
            border: none;
            background: #263d68;
            color: white;
            padding: 11px 17px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .secundario {
            background: #64748b;
        }

        .eliminar {
            background: #c83e3e;
        }

        .fila {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 12px;
            margin: 15px 0;
            align-items: end;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .resumen {
            background: #eef2fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 25px;
        }

        .total {
            font-size: 26px;
            font-weight: bold;
            color: #263d68;
        }

        .error {
            color: #b42318;
            margin-bottom: 15px;
        }

        @media(max-width: 700px) {
            body { padding: 12px; }
            .fila { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<div class="contenedor">

    <h1>Papelería Aylen</h1>
    <h2>Registrar nueva venta</h2>

    <a href="{{ route('ventas.index') }}" class="boton secundario">
        Volver al historial
    </a>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf

        <div style="margin-top:25px;">
            <label for="metodo_pago">Método de pago</label>

            <select name="metodo_pago" id="metodo_pago" required>
                <option value="">Seleccionar método</option>
                <option value="EFECTIVO">Efectivo</option>
                <option value="TARJETA">Tarjeta</option>
                <option value="TRANSFERENCIA">Transferencia</option>
                <option value="OTRO">Otro</option>
            </select>
        </div>

        <h3>Productos de la venta</h3>

        <div id="productos">

            <div class="fila producto-fila">
                <div>
                    <label>Producto</label>

                    <select name="productos[0][id]"
                            class="producto"
                            required
                            onchange="calcularTotal()">

                        <option value="">Seleccionar producto</option>

                        @foreach($productos as $producto)
                            <option
                                value="{{ $producto->id }}"
                                data-precio="{{ $producto->precio_venta }}"
                                data-existencia="{{ $producto->existencia }}">

                                {{ $producto->nombre }}
                                - ${{ number_format($producto->precio_venta, 2) }}
                                (Existencia: {{ $producto->existencia }})

                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label>Cantidad</label>

                    <input type="number"
                           name="productos[0][cantidad]"
                           class="cantidad"
                           min="1"
                           value="1"
                           required
                           oninput="calcularTotal()">
                </div>

                <button type="button"
                        class="boton eliminar"
                        onclick="eliminarProducto(this)">
                    Quitar
                </button>
            </div>

        </div>

        <button type="button"
                class="boton secundario"
                onclick="agregarProducto()">
            + Agregar producto
        </button>

        <div class="resumen">
            <p>Subtotal estimado:</p>
            <div class="total" id="total">$0.00</div>
            <p>El importe definitivo será calculado en el servidor.</p>
        </div>

        <br>

        <button type="submit" class="boton">
            Registrar venta
        </button>

    </form>

</div>

<script>
    let contador = 1;

    function agregarProducto() {
        const contenedor = document.getElementById('productos');

        const fila = document.createElement('div');
        fila.className = 'fila producto-fila';

        fila.innerHTML = `
            <div>
                <label>Producto</label>
                <select name="productos[${contador}][id]"
                        class="producto"
                        required
                        onchange="calcularTotal()">

                    <option value="">Seleccionar producto</option>

                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}"
                                data-precio="{{ $producto->precio_venta }}"
                                data-existencia="{{ $producto->existencia }}">
                            {{ $producto->nombre }}
                            - ${{ number_format($producto->precio_venta, 2) }}
                            (Existencia: {{ $producto->existencia }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Cantidad</label>
                <input type="number"
                       name="productos[${contador}][cantidad]"
                       class="cantidad"
                       min="1"
                       value="1"
                       required
                       oninput="calcularTotal()">
            </div>

            <button type="button"
                    class="boton eliminar"
                    onclick="eliminarProducto(this)">
                Quitar
            </button>
        `;

        contenedor.appendChild(fila);
        contador++;
    }

    function eliminarProducto(boton) {
        const filas = document.querySelectorAll('.producto-fila');

        if (filas.length > 1) {
            boton.closest('.producto-fila').remove();
            calcularTotal();
        }
    }

    function calcularTotal() {
        let total = 0;

        document.querySelectorAll('.producto-fila').forEach(fila => {
            const select = fila.querySelector('.producto');
            const cantidad = fila.querySelector('.cantidad');

            const opcion = select.options[select.selectedIndex];

            if (opcion && opcion.dataset.precio) {
                const precio = parseFloat(opcion.dataset.precio);
                const unidades = parseInt(cantidad.value) || 0;

                total += precio * unidades;
            }
        });

        document.getElementById('total').textContent =
            '$' + total.toFixed(2);
    }
</script>

</body>
</html>
