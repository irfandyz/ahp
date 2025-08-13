<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->streetAddress(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'npwp' => fake()->numerify('##.###.###.#-###.###'),
            'pic_name' => fake()->name(),
            'pic_phone' => fake()->phoneNumber(),
        ];
    }
}
