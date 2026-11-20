
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo producto | Papelería Aylen</title>
</head>
<body style="font-family:Arial;margin:35px;background:#f4f6fa">
<h1>Registrar producto</h1>

@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('productos.store') }}">
    @csrf

    <label>Código</label><br>
    <input name="codigo" value="{{ old('codigo') }}" required><br><br>

    <label>Nombre</label><br>
    <input name="nombre" value="{{ old('nombre') }}" required><br><br>

    <label>Categoría</label><br>
    <select name="categoria_id" required>
        <option value="">Selecciona</option>
        @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
        @endforeach
    </select><br><br>

    <label>Proveedor</label><br>
    <select name="proveedor_id">
        <option value="">Sin proveedor</option>
        @foreach($proveedores as $proveedor)
            <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }}</option>
        @endforeach
    </select><br><br>

    <label>Descripción</label><br>
    <textarea name="descripcion">{{ old('descripcion') }}</textarea><br><br>

    <label>Precio de venta</label><br>
    <input type="number" step="0.01" min="0" name="precio_venta" required><br><br>

    <label>Existencia</label><br>
    <input type="number" min="0" name="existencia" required><br><br>

    <button type="submit">Guardar producto</button>
    <a href="{{ route('productos.index') }}">Cancelar</a>
</form>
</body>
</html>
