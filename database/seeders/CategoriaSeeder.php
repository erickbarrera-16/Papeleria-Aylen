<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insertOrIgnore([
            [
                'nombre' => 'Papelería',
                'descripcion' => 'Artículos escolares y de oficina',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Escritura',
                'descripcion' => 'Plumas, lápices y marcadores',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Material escolar',
                'descripcion' => 'Material para actividades escolares',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Oficina',
                'descripcion' => 'Artículos para oficina',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
