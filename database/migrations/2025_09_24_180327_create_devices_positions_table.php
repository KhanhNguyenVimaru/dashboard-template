<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices_positions', function (Blueprint $table) {
            $table->id();
            $table->enum('device_type', ['router', 'iot_device']);
            $table->float('x'); // Tọa độ X
            $table->float('y'); // Tọa độ Y

            // Khóa ngoại môi trường
            $table->foreignId('environment_id')
                  ->constrained('environments')
                  ->onDelete('cascade');

            // Khóa ngoại tới IoT Device
            $table->unsignedBigInteger('iot_device_id')->nullable();
            $table->foreign('iot_device_id')
                  ->references('id')
                  ->on('iot_devices')
                  ->onDelete('set null');

            // Khóa ngoại tới Router
            $table->unsignedBigInteger('router_id')->nullable();
            $table->foreign('router_id')
                  ->references('id')
                  ->on('routers')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices_positions');
    }
};
