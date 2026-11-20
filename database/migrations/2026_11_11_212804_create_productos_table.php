
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('proveedor_id')
                ->nullable()
                ->constrained('proveedores')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('codigo', 40)->unique();
            $table->string('nombre', 120);
            $table->string('descripcion', 255)->nullable();
            $table->decimal('precio_venta', 10, 2);
            $table->unsignedInteger('existencia')->default(0);
            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index('categoria_id');
            $table->index('proveedor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
