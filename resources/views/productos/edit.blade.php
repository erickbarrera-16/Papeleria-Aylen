
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto | Papelería Aylen</title>
</head>
<body style="font-family:Arial;margin:35px;background:#f4f6fa">
<h1>Editar producto</h1>

@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('productos.update', $producto) }}">
    @csrf
    @method('PUT')

    <label>Código</label><br>
    <input name="codigo" value="{{ old('codigo', $producto->codigo) }}" required><br><br>

    <label>Nombre</label><br>
    <input name="nombre" value="{{ old('nombre', $producto->nombre) }}" required><br><br>

    <label>Categoría</label><br>
    <select name="categoria_id" required>
        @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}" @selected($producto->categoria_id == $categoria->id)>
                {{ $categoria->nombre }}
            </option>
        @endforeach
    </select><br><br>

    <label>Proveedor</label><br>
    <select name="proveedor_id">
        <option value="">Sin proveedor</option>
        @foreach($proveedores as $proveedor)
            <option value="{{ $proveedor->id }}" @selected($producto->proveedor_id == $proveedor->id)>
                {{ $proveedor->razon_social }}
            </option>
        @endforeach
    </select><br><br>

    <label>Descripción</label><br>
    <textarea name="descripcion">{{ old('descripcion', $producto->descripcion) }}</textarea><br><br>

    <label>Precio</label><br>
    <input type="number" step="0.01" min="0" name="precio_venta"
           value="{{ old('precio_venta', $producto->precio_venta) }}" required><br><br>

    <label>Existencia</label><br>
    <input type="number" min="0" name="existencia"
           value="{{ old('existencia', $producto->existencia) }}" required><br><br>

    <button type="submit">Actualizar</button>
    <a href="{{ route('productos.index') }}">Cancelar</a>
</form>
</body>
</html>
