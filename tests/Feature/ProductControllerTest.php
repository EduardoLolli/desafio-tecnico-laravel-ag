<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductFamily;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::tags(['products_list'])->flush();
    }
    public function test_list_products(): void
    {
        $family = ProductFamily::factory()->create();
        Product::factory()->count(3)->create(['family_code' => $family->code]);
        $response = $this->withoutMiddleware()->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Produtos disponíveis',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'code',
                        'name',
                        'description',
                        'price',
                        'stock_qtt',
                        'barcode',
                        'family',
                    ]
                ]
            ]);
    }

    public function test_404_no_products_found(): void
    {
        $response = $this->withoutMiddleware()
            ->getJson('/api/products');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Nenhum produto foi encontrado',
                'data'    => [],
            ]);
    }
    public function test_create_a_product()
    {
        $family = ProductFamily::factory()->create();
        $payload = [
            'code'          => 'PROD-123',
            'barcode'       => '7891234567890',
            'name'          => 'Produto Teste',
            'description'   => 'Descrição do produto teste',
            'stock_qtt'     => 10,
            'price'         => 99.90,
            'quantity'      => 10,
            'family_code'   => $family->code,
        ];

        $response = $this->withoutMiddleware()
            ->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Produto criado com sucesso',
                'data' => [
                    'code'          => 'PROD-123',
                    'barcode'       => '7891234567890',
                    'name'          => 'Produto Teste',
                    'description'   => 'Descrição do produto teste',
                    'stock_qtt'     => 10,
                    'price'         => 99.90,
                    'family'        => [
                        'code' => $family->code,
                        'name' => $family->name,
                    ],
                ]
            ]);

        $this->assertDatabaseHas('products', ['code' => 'PROD-123']);
    }

    public function test_show_product_by_code()
    {

        $family = ProductFamily::factory()->create();
        $product = Product::factory()->create(['family_code' => $family->code]);

        $response = $this->withoutMiddleware()->getJson("/api/products/{$product->code}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => '',
                'data' => [
                    'code' => $product->code,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock_qtt' => $product->stock_qtt,
                    'barcode' => $product->barcode,
                    'family' => [
                        'code' => $family->code,
                        'name' => $family->name,
                    ]
                ]
            ]);
    }

    public function test_show_404_product_not_exist()
    {
        $response = $this->withoutMiddleware()->getJson('/api/products/CODIGO-INEXISTENTE');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_update_product_by_code()
    {
        $family = ProductFamily::factory()->create();
        $product = Product::factory()->create(
            [
                'name' => 'Nome Antigo',
                'family_code' => $family->code
            ]
        );

        $payload = ['name' => 'Nome Atualizado'];

        $response = $this->withoutMiddleware()->putJson("/api/products/{$product->code}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Produto atualizado com sucesso',
            ]);

        $this->assertDatabaseHas('products', [
            'code' => $product->code,
            'name' => 'Nome Atualizado',
        ]);
    }

    public function test_update_404_code_not_found()
    {
        $response = $this->withoutMiddleware()
            ->putJson('/api/products/CODIGO-INEXISTENTE', ['name' => 'Nome novo']);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_destroy_product_by_code()
    {
        $product = Product::factory()->create(['code' => 'COD-123']);

        $response = $this->withoutMiddleware()
            ->deleteJson("/api/products/{$product->code}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Produto removido com sucesso.',
            ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_destroy_product_by_barcode()
    {
        $product = Product::factory()->create([
            'code'    => 'COD-123',
            'barcode' => '7890000000001',
        ]);

        $response = $this->withoutMiddleware()
            ->deleteJson("/api/products/{$product->barcode}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_destroy_404_identifier_not_found()
    {
        $response = $this->withoutMiddleware()
            ->deleteJson('/api/products/IDENTIFICADOR-INVALIDO');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }
}
