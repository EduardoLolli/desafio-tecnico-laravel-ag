<?php

namespace Tests\Feature;

use App\Models\ProductFamily;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductFamilyControllerTest extends TestCase
{
    use RefreshDatabase;



    public function test_list_family_groups()
    {
        ProductFamily::factory()->count(2)->create();
        $response = $this->withoutMiddleware()
            ->getJson('/api/product-families');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Famílias de produtos disponíveis'
            ])->assertJsonCount(2, 'data');
    }

    public function test_list_family_by_name_filter()
    {
        ProductFamily::factory()->create(['name' => 'Eletrônicos']);
        ProductFamily::factory()->create(['name' => 'Eletrodomésticos']);

        $response = $this->withoutMiddleware()
            ->getJson('/api/product-families?name=Eletrodo');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Eletrodomésticos');
    }

    public function test_create_new_family()
    {
        $payload = ProductFamily::factory()->make()->toArray();
        $response = $this->withoutMiddleware()
            ->postJson('/api/product-families', $payload);

        $response->assertStatus(201)->assertJson([
            'code' => $payload['code'],
            'name' => $payload['name']
        ]);

        $this->assertDatabaseHas(
            'product_families',
            [
                'code' => $payload['code'],
                'name' => $payload['name']
            ]
        );
    }

    public function test_show_error_duplicated_family()
    {
        $existing = ProductFamily::factory()->create();

        $payload = [
            'code' => $existing->code,
            'name' => 'Outro Nome',
        ];

        $response = $this->withoutMiddleware()
            ->postJson('/api/product-families', $payload);

        $response->assertStatus(422)->assertJsonValidationErrors(['code']);
    }
}
