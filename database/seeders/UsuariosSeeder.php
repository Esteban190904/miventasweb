<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Administrador',
                'correo' => 'admin@innovanegocios.com',
                'password' => Hash::make('admin123'),
                'rol' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Usuario Prueba',
                'correo' => 'usuario@innovanegocios.com',
                'password' => Hash::make('usuario123'),
                'rol' => 'usuario',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
