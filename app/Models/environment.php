<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class environment extends Model
{
    /** @use HasFactory<\Database\Factories\EnvironmentFactory> */
    use HasFactory;
    
    /**
     * Lấy tất cả các thiết bị IoT trong môi trường này
     */
    public function iotDevices(): HasMany
    {
        return $this->hasMany(IotDevice::class, 'environment_id');
    }
    
    /**
     * Lấy tất cả các router trong môi trường này
     */
    public function routers(): HasMany
    {
        return $this->hasMany(Router::class, 'environment_id');
    }
    
    /**
     * Lấy tất cả các vị trí thiết bị trong môi trường này
     */
    public function devicePositions(): HasMany
    {
        return $this->hasMany(Devices_position::class, 'environment_id');
    }
}
