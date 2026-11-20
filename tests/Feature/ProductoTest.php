<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_puede_visualizar_el_catalogo_de_productos(): void
    {
        $response = $this->get('/productos');

        $response->assertStatus(200);
        $response->assertSee('Administración de productos');
    }

    public function test_se_puede_registrar_un_producto(): void
    {
        $categoria = Categoria::create([
            'nombre' => 'Papelería',
            'descripcion' => 'Artículos escolares',
            'activa' => true,
        ]);

        $response = $this->post('/productos', [
            'categoria_id' => $categoria->id,
            'proveedor_id' => null,
            'codigo' => 'TEST001',
            'nombre' => 'Cuaderno profesional',
            'descripcion' => 'Cuaderno de 100 hojas',
            'precio_venta' => 35.50,
            'existencia' => 20,
        ]);

        $response->assertRedirect('/productos');

        $this->assertDatabaseHas('productos', [
            'codigo' => 'TEST001',
            'nombre' => 'Cuaderno profesional',
        ]);
    }

    public function test_no_se_puede_registrar_un_producto_sin_nombre(): void
    {
        $categoria = Categoria::create([
            'nombre' => 'Escritura',
            'descripcion' => 'Material para escribir',
            'activa' => true,
        ]);

        $response = $this->from('/productos/create')->post('/productos', [
            'categoria_id' => $categoria->id,
            'codigo' => 'TEST002',
            'nombre' => '',
            'precio_venta' => 15,
            'existencia' => 10,
        ]);

        $response->assertSessionHasErrors('nombre');
    }
}
