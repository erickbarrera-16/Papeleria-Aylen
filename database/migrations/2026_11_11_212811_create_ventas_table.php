
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('cliente_id')
                ->nullable()
                ->constrained('clientes')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->dateTime('fecha_venta')->useCurrent();

            $table->enum('metodo_pago', [
                'EFECTIVO',
                'TARJETA',
                'TRANSFERENCIA',
                'OTRO'
            ])->default('EFECTIVO');

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->enum('estado', [
                'COMPLETADA',
                'CANCELADA'
            ])->default('COMPLETADA');

            $table->timestamps();
            $table->index('fecha_venta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
