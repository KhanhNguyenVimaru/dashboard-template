<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IotDevice>
 */
class IotDeviceFactory extends Factory
{
    public function definition(): array
    {
        $data = [
            'device_name'      => $this->faker->unique()->words(2, true),
            'device_type'      => $this->faker->randomElement(['sensor', 'plc', 'cnc', 'rfid', 'robot', 'hmi', 'other']),
            'serial_number'    => strtoupper(Str::random(12)),
            'ip_address'       => $this->faker->optional()->ipv4(),
            'port'             => $this->faker->optional()->numberBetween(1024, 65535),
            'mac_address'      => $this->faker->optional()->macAddress(),
            'manufacturer'     => $this->faker->optional()->company(),
            'model'            => $this->faker->optional()->bothify('Model-###??'),
            'config'           => $this->faker->optional()->randomElement([
                json_encode(['threshold' => $this->faker->numberBetween(1, 100)]),
                json_encode(['username' => 'admin', 'password' => 'secret']),
            ]),
            'is_active'        => $this->faker->boolean(85),
            'coverage'         => $this->faker->randomFloat(3, 0, 500),
            'protocol'         => $this->faker->randomElement(['mqtt', 'http', 'modbus', 'coap', 'tcp', 'udp', 'other']),
            'firmware_version' => $this->faker->optional()->numerify('v#.#.#'),
            'last_seen'        => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'battery_level'    => $this->faker->optional()->numberBetween(0, 100),
            'rssi'             => $this->faker->optional()->numberBetween(-120, -30),
        ];

        // loại bỏ các field null
        return array_filter($data, fn ($value) => !is_null($value));
    }
}
