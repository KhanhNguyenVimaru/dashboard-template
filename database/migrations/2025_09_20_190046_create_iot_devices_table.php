<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iot_devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_name');
            $table->enum('device_type', ['sensor', 'plc', 'cnc', 'rfid', 'robot', 'hmi', 'other']);
            $table->string('serial_number', 100)->unique();
            $table->ipAddress('ip_address')->nullable();
            $table->unsignedSmallInteger('port')->nullable();
            $table->string('mac_address', 17)->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->json('config')->nullable(); // lưu cấu hình, nhưng mã hóa nếu chứa secret
            $table->boolean('is_active')->default(true);
            $table->decimal('coverage', 8, 3)->default(0); // bán kính phủ
            $table->enum('protocol', ['mqtt', 'http', 'modbus', 'coap', 'tcp', 'udp', 'other'])->default('mqtt');

            $table->string('firmware_version')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->unsignedTinyInteger('battery_level')->nullable(); // 0-100%
            $table->smallInteger('rssi')->nullable(); // dBm

            $table->softDeletes();
            $table->timestamps();

            // indexes
            $table->index(['device_type', 'is_active']);
            $table->index('last_seen');
            $table->unique(['ip_address', 'port'], 'iot_devices_ip_port_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iot_devices');
    }
};
