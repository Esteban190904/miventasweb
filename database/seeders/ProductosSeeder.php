<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('productos')->insert([
            [
                'nombre' => 'Laptop HP 14"',
                'descripcion' => 'Laptop con procesador Ryzen 5, 8GB RAM, SSD 256GB',
                'precio' => 2300.00,
                'stock' => 15,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Mouse Inalámbrico Logitech',
                'descripcion' => 'Mouse ergonómico con conexión USB y Bluetooth',
                'precio' => 95.00,
                'stock' => 40,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
