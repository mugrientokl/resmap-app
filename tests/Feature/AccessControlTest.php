<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_force_internal_urls(): void
    {
        foreach (['/productos', '/pos', '/categorias', '/clientes', '/solicitudes-web', '/usuarios'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_protected_pages_are_not_cached_and_logout_removes_access(): void
    {
        $admin = User::factory()->create(['rol' => 'Administrador']);

        $response = $this->actingAs($admin)->get('/productos');

        $response->assertOk();
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));

        $this->actingAs($admin)->post('/logout')->assertRedirect('/');
        $this->get('/productos')->assertRedirect('/login');
    }

    public function test_seller_cannot_create_or_delete_products_or_categories(): void
    {
        $seller = User::factory()->create(['rol' => 'Vendedor']);

        $this->actingAs($seller)->get('/productos/crear')->assertForbidden();
        $this->actingAs($seller)->post('/productos', [])->assertForbidden();
        $this->actingAs($seller)->get('/categorias/crear')->assertForbidden();
        $this->actingAs($seller)->post('/categorias', [])->assertForbidden();
    }
}
