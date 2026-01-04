<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria; // 👈 IMPORTANTE
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Josue',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'), // 👈 string
        ]);

        Categoria::create(['nombre' => 'Alimentación', 'tipo' => 'gasto']);
        Categoria::create(['nombre' => 'Transporte', 'tipo' => 'gasto']);
        Categoria::create(['nombre' => 'Salud', 'tipo' => 'gasto']);
        Categoria::create(['nombre' => 'Entrenamiento', 'tipo' => 'gasto']);
        Categoria::create(['nombre' => 'Sueldos', 'tipo' => 'gasto']);
        Categoria::create(['nombre' => 'Inversiones', 'tipo' => 'gasto']);
        Categoria::create(['nombre' => 'Otros', 'tipo' => 'ingreso']);
        Categoria::create(['nombre' => 'Ahorros', 'tipo' => 'ingreso']);
        Categoria::create(['nombre' => 'Otros ingresos', 'tipo' => 'ingreso']);
        Categoria::create(['nombre' => 'Otros gastos', 'tipo' => 'gasto']);
    }
}
