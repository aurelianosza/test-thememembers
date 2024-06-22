<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public static function generateRandomProductCode() : string
    {
        return join("-", [
            strtoupper(substr(str_pad(fake()->word(), 3, "A", STR_PAD_LEFT), 0, 3)),
            str_pad(fake()->numberBetween(0, 999), 3, 0, STR_PAD_LEFT)
        ]);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "code"          => self::generateRandomProductCode(),
            "name"          => fake()->name,
            "price"         => fake()->numberBetween(1, 99999) / 100,
            "description"   => fake()->sentence()
        ];
    }
}
