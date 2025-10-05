<?php

namespace Database\Factories;

use App\Models\Router;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Router>
 */
class RouterFactory extends Factory
{
    protected $model = Router::class;

    public function definition(): array
    {
        return [
            'mac_address'      => $this->faker->unique()->macAddress(),
            'name'             => ucfirst($this->faker->unique()->word()) . ' Router',
            'port'             => $this->faker->optional()->numberBetween(1024, 65535),
            'ip_address'       => $this->faker->unique()->ipv4(),
            'location'         => $this->faker->optional()->city(),
            'model'            => $this->faker->optional()->bothify('RT-####'),
            'manufacturer'     => $this->faker->optional()->company(),
            'firmware_version' => $this->faker->optional()->numerify('v#.#.#'),
            'status'           => $this->faker->boolean(70) ? 'online' : 'offline',
            'bandwidth'        => $this->faker->optional()->numberBetween(50, 2000),
            'coverage'         => $this->faker->optional()->randomFloat(2, 5, 300),
        ];
    }

    public function online(): self
    {
        return $this->state(fn () => ['status' => 'online']);
    }

    public function offline(): self
    {
        return $this->state(fn () => ['status' => 'offline']);
    }
}
