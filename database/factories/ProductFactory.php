<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        $category = fake()->randomElement(['food', 'drink']);
        
        return [
            'product_name' => fake()->words(3, true),
            'price' => fake()->numberBetween(10, 25) * 1000,
            'category' => $category,
            'image' => 'https://picsum.photos/seed/' . fake()->numberBetween(1, 1000) . '/640/480',
            'stock' => fake()->numberBetween(0, 50)
        ];
    }
}
