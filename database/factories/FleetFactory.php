<?php

namespace Database\Factories;

use App\Models\FleetType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipping>
 */
class FleetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plateNumbers = [
            'B 9450 scp',
            'B 9464 scp',
            'B 9482 scp',
            'B 9677 scp',
            'B 9673 scp',
        ];

        return [
            'fleet_type_id' => FleetType::inRandomOrder()->first()->id,
            'plate_number' => $this->faker->randomElement($plateNumbers),
            'description' => $this->faker->sentence(),
        ];
    }
}
