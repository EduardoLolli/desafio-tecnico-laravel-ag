<?php

namespace Database\Factories;

use App\Models\ProductFamily;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductFamily>
 */
class ProductFamilyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('??-####')),
            'name' => ucfirst($this->faker->words(2, true)),
        ];
    }
}
