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
        Schema::create('paths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('environment_id');
            $table->foreign('environment_id')->references('id')->on('environments')->onDelete('cascade');

            $table->unsignedBigInteger('start_device_id');
            $table->unsignedBigInteger('end_device_id');
            $table->enum('start_device_type', ['router', 'iot_device']);
            $table->enum('end_device_type', ['router', 'iot_device']);
            $table->decimal('length', 8, 3);

            $table->foreign('start_device_id')
                  ->references('id')
                  ->on('iot_devices')
                  ->onDelete('cascade')
                  ->where('start_device_type', 'iot_device');

            $table->foreign('end_device_id')
                  ->references('id')
                  ->on('iot_devices')
                  ->onDelete('cascade')
                  ->where('end_device_type', 'iot_device');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paths');
    }
};
