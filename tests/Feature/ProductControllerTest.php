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

    public function test_create_new_product(): void {

    
    }
    public function test_update_product(): void {}
    public function test_delete_product(): void {}
    public function test_list_by_code(): void {}
}
