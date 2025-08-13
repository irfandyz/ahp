<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company' => fake()->company(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'pic' => fake()->name(),
            'title_pic' => fake()->jobTitle(),
            'phone' => fake()->phoneNumber(),
            'moda' => fake()->randomElement(['Truck', 'Ship', 'Train', 'Air']),
            'fleet' => fake()->randomElement(['Small', 'Medium', 'Large']),
            'area_service_coverage' => fake()->city() . ', ' . fake()->city(),
        ];
    }
}
