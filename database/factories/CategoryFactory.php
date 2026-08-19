<?php

namespace Database\Factories;

use App\Enums\CategoryKind;
use App\Enums\CategoryLayout;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($name),
            'name' => $name,
            'latin_name' => Str::upper($name),
            'subtitle' => null,
            'description' => fake()->sentence(),
            'kind' => CategoryKind::Drink,
            'layout' => CategoryLayout::Grid,
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }

    public function hookah(): static
    {
        return $this->state(fn () => [
            'kind' => CategoryKind::Hookah,
            'layout' => CategoryLayout::List,
            'price' => fake()->numberBetween(1, 30) * 10_000,
        ]);
    }

    public function onLanding(int $order = 1): static
    {
        return $this->state(fn () => ['card_order' => $order]);
    }
}
