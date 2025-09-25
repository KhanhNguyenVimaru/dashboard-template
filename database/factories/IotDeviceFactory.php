<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IotDevice>
 */
class IotDeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $device_types = ['sensor', 'plc', 'cnc', 'rfid', 'robot', 'hmi', 'other'];
        $protocols = ['mqtt', 'http', 'modbus', 'coap', 'tcp', 'udp', 'other'];

        return [
            'device_name'      => $this->faker->words(2, true),
            'device_type'      => $this->faker->randomElement($device_types),
            'serial_number'    => strtoupper(Str::random(10)),
            'ip_address'       => $this->faker->optional()->ipv4(),
            'port'             => $this->faker->optional()->numberBetween(1024, 65535),
            'mac_address'      => $this->faker->optional()->macAddress(),
            'manufacturer'     => $this->faker->optional()->company(),
            'model'            => $this->faker->optional()->bothify('Model-###??'),
            'config'           => $this->faker->optional()->randomElement([
                json_encode(['username' => 'admin', 'password' => 'secret']),
                json_encode(['threshold' => rand(1, 100)]),
                null
            ]),
            'is_active'        => $this->faker->boolean(90),
            'coverage'         => $this->faker->randomFloat(3, 0, 500.000),
            'protocol'         => $this->faker->randomElement($protocols),
            'firmware_version' => $this->faker->optional()->semver(),
            'last_seen'        => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'battery_level'    => $this->faker->optional()->numberBetween(0, 100),
            'rssi'             => $this->faker->optional()->numberBetween(-120, -30),
        ];
    }
}
