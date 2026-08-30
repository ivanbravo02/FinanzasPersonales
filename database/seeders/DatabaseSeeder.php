<?php

namespace Database\Seeders;

use App\Models\CategoriaProducto;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $admin   = Role::create(['nombre' => 'admin']);
        $gerente = Role::create(['nombre' => 'gerente']);
        $cajero  = Role::create(['nombre' => 'cajero']);

        // Usuarios
        User::create(['name' => 'Administrador', 'email' => 'admin@pos.com',   'password' => Hash::make('password'), 'role_id' => $admin->id]);
        User::create(['name' => 'Gerente',        'email' => 'gerente@pos.com', 'password' => Hash::make('password'), 'role_id' => $gerente->id]);
        User::create(['name' => 'Cajero 1',       'email' => 'cajero@pos.com',  'password' => Hash::make('password'), 'role_id' => $cajero->id]);

        // Categorías
        $bebidas  = CategoriaProducto::create(['nombre' => 'Bebidas',   'color' => '#3b82f6']);
        $comida   = CategoriaProducto::create(['nombre' => 'Comida',    'color' => '#f59e0b']);
        $limpieza = CategoriaProducto::create(['nombre' => 'Limpieza',  'color' => '#10b981']);
        $otros    = CategoriaProducto::create(['nombre' => 'Otros',     'color' => '#8b5cf6']);

        // Productos
        $productos = [
            ['categoria_id' => $bebidas->id,  'nombre' => 'Agua 500ml',      'codigo' => 'BEB001', 'precio_compra' => 5,  'precio_venta' => 12,  'stock' => 100],
            ['categoria_id' => $bebidas->id,  'nombre' => 'Refresco 600ml',  'codigo' => 'BEB002', 'precio_compra' => 8,  'precio_venta' => 18,  'stock' => 80],
            ['categoria_id' => $bebidas->id,  'nombre' => 'Jugo 1L',         'codigo' => 'BEB003', 'precio_compra' => 15, 'precio_venta' => 28,  'stock' => 50],
            ['categoria_id' => $comida->id,   'nombre' => 'Pan Bimbo',       'codigo' => 'COM001', 'precio_compra' => 30, 'precio_venta' => 45,  'stock' => 30],
            ['categoria_id' => $comida->id,   'nombre' => 'Sabritas 45g',    'codigo' => 'COM002', 'precio_compra' => 10, 'precio_venta' => 18,  'stock' => 60],
            ['categoria_id' => $limpieza->id, 'nombre' => 'Jabón Líquido',   'codigo' => 'LIM001', 'precio_compra' => 20, 'precio_venta' => 35,  'stock' => 25],
            ['categoria_id' => $limpieza->id, 'nombre' => 'Papel Higiénico', 'codigo' => 'LIM002', 'precio_compra' => 40, 'precio_venta' => 65,  'stock' => 40],
            ['categoria_id' => $otros->id,    'nombre' => 'Pilas AA x2',     'codigo' => 'OTR001', 'precio_compra' => 18, 'precio_venta' => 35,  'stock' => 20],
        ];

        foreach ($productos as $p) {
            Producto::create(array_merge($p, ['stock_minimo' => 5]));
        }

        // Clientes
        Cliente::create(['nombre' => 'Cliente General', 'email' => null]);
        Cliente::create(['nombre' => 'Juan Pérez',      'telefono' => '5551234567', 'email' => 'juan@email.com']);
        Cliente::create(['nombre' => 'María López',     'telefono' => '5559876543', 'rfc' => 'LOPM800101XXX']);
    }
}
