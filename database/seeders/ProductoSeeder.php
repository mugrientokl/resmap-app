<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        Producto::create([
            'codigo_barra' => 'FIL-CAT-1R0716',
            'nombre' => 'Filtro de Aceite de Alta Eficiencia CAT',
            'descripcion' => 'Filtro original para motores C9 y C15.',
            'precio' => 45990.00,
            'stock' => 25,
            'stock_critico' => 5,
            'id_categoria' => 1
        ]);

        Producto::create([
            'codigo_barra' => 'ROD-EXC-320',
            'nombre' => 'Rodillo Inferior Excavadora Komatsu/CAT',
            'descripcion' => 'Rodillo de carga para tren de rodaje de excavadora mediana.',
            'precio' => 189990.00,
            'stock' => 8,
            'stock_critico' => 2,
            'id_categoria' => 2
        ]);

        Producto::create([
            'codigo_barra' => 'HID-MANG-HP2',
            'nombre' => 'Manguera Hidráulica Alta Presión 1 Pulgada',
            'descripcion' => 'Manguera reforzada para sistemas hidráulicos exigentes (por metro).',
            'precio' => 28500.00,
            'stock' => 50,
            'stock_critico' => 10,
            'id_categoria' => 3
        ]);

        Producto::create([
            'codigo_barra' => 'TUR-VOLV-D13',
            'nombre' => 'Turbocompresor Motor Volvo D13',
            'descripcion' => 'Turbo alimentador de repuesto para camiones tolva y maquinaria.',
            'precio' => 850000.00,
            'stock' => 3,
            'stock_critico' => 1,
            'id_categoria' => 4
        ]);
    }
}