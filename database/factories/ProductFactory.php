<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductFamily;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'        => 'PRD-' . fake()->unique()->numberBetween(1000, 9999),
            'name'        => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price'       => fake()->randomFloat(2, 5, 500),
            'stock_qtt'   => fake()->numberBetween(0, 150),
            'barcode'     => fake()->unique()->ean13(),
            'family_code' => ProductFamily::factory()->create()->code,
        ];
    }
}
