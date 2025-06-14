<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ventas')->insert([
            [
                'usuario_id' => 1,
                'fecha_venta' => now(),
                'total' => 2395.00,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
