<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetalleVentasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('detalle_ventas')->insert([
            [
                'venta_id' => 1,
                'producto_id' => 1,
                'cantidad' => 1,
                'precio_unitario' => 2300.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'venta_id' => 1,
                'producto_id' => 2,
                'cantidad' => 1,
                'precio_unitario' => 95.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
