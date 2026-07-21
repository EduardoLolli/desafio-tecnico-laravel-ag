<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductFamily;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $families = ProductFamily::factory()->count(5)->create();

        Product::factory()->count(50)->make()->each(function ($product) use ($families) {
            $product->family_code = $families->random()->code;
            $product->save();
        });
    }
}
