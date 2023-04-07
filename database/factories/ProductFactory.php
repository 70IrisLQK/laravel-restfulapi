<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {
        $name = fake()->unique()->sentence(8);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(1),
            'quantity' => fake()->numberBetween(1, 50),
            'status' => fake()->randomNumber([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
            'image' => fake()->randomNumber(['product-1.jpg', 'product-2.jpg', 'product-3.jpg', 'product-4.jpg', 'product-5.jpg', 'product-6.jpg',]),
            'seller_id' => User::all()->random()->id,
        ];
    }
}