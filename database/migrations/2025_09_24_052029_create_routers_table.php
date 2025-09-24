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
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address', 17);
            $table->string('name');
            $table->string('ip_address')->unique();
            $table->string('location')->nullable();
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('firmware_version')->nullable();
            $table->enum('status', ['online', 'offline'])->default('offline');
            $table->integer('bandwidth')->nullable(); // Mbps
            $table->float('coverage')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routers');
    }
};
