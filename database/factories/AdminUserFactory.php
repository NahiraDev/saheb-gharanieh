<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminUser>
 */
class AdminUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'مدیر کافه',
            'username' => fake()->unique()->userName(),
            'password' => 'secret123',   // hashed by the model cast
        ];
    }
}
