<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'name' => fake()->productName,
            'description' => fake()->sentence(15),
            'image' => fake()->placeholder(),
            'price' => fake()->randomFloat(1, 1, 499),
            'compare_price' => fake()->randomFloat(1, 499, 999),
            'category_id' => Category::inRandomOrder()->first()->id,
            'store_id' => Store::inRandomOrder()->first()->id,
            'featured' => rand(0, 1),
            'status' => Arr::random(['active', 'draft', 'archived']),
        ];
    }
}
