<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InventoryMovementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_adjust_stock_and_the_movement_records_the_actor(): void
    {
        $admin = User::factory()->create(['rol' => 'Administrador']);
        $categoryId = DB::table('categorias')->insertGetId([
            'nombre_categoria' => 'Pruebas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $productId = DB::table('productos')->insertGetId([
            'codigo_barra' => 'TEST-001',
            'nombre' => 'Producto de prueba',
            'precio' => 1190,
            'stock' => 3,
            'stock_critico' => 1,
            'id_categoria' => $categoryId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)->post(route('inventario.movimientos.store'), [
            'id_producto' => $productId,
            'stock_nuevo' => 8,
            'motivo' => 'Conteo físico',
        ])->assertRedirect(route('inventario.movimientos'));

        $this->assertDatabaseHas('inventario_movimientos', [
            'id_producto' => $productId,
            'user_id' => $admin->id,
            'cantidad' => 5,
            'stock_anterior' => 3,
            'stock_nuevo' => 8,
            'motivo' => 'Conteo físico',
        ]);
    }
}
