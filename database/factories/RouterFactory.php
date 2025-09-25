<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Router>
 */
class RouterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mac_address'      => $this->faker->macAddress(),
            'name'             => $this->faker->domainWord() . '-router',
            'ip_address'       => $this->faker->unique()->ipv4(),
            'location'         => $this->faker->optional()->city(),
            'model'            => $this->faker->optional()->bothify('R-###??'),
            'manufacturer'     => $this->faker->optional()->company(),
            'firmware_version' => $this->faker->optional()->semver(),
            'status'           => $this->faker->randomElement(['online', 'offline']),
            'bandwidth'        => $this->faker->optional()->numberBetween(50, 1000), // Mbps
            'coverage'         => $this->faker->optional()->randomFloat(2, 10, 500), // m2 or radius
        ];
    }
}
