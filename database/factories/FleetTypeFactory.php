<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FleetType>
 */
class FleetTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fleetTypes = [
            'CDE BOX',
            'CDE BAK',
            'CDD BOX',
            'CDD BAK',
            'CDDL BOX',
            'CDDL BAK',
            'FUSO Box',
            'FUSO BAK',
            'FUSO L BOX',
            'FUSO L BAK',
            'WINGBOX',
            'TRONTON',
            'TRAILER',
            'CONTAINER 20 FEET',
            'CONTAINER 40 FEET',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($fleetTypes),
            'description' => $this->faker->sentence(),
        ];
    }
}
