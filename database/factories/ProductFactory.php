<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(2, true),
            'latin_name' => null,
            'description' => null,
            'price' => null,
            'image_path' => null,
            'glyph' => null,
            'sort_order' => fake()->numberBetween(1, 30),
            'is_active' => true,
            'is_featured' => false,
            'is_available' => true,
        ];
    }

    public function priced(?int $price = null): static
    {
        return $this->state(fn () => [
            'price' => $price ?? fake()->numberBetween(5, 40) * 10_000,
        ]);
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
