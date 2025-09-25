<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Devices_position extends Model
{
    /** @use HasFactory<\Database\Factories\DevicesPositionFactory> */
    use HasFactory;
    
    /**
     * Lấy môi trường chứa vị trí này
     */
    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }
    
    /**
     * Lấy thiết bị IoT ở vị trí này (nếu có)
     */
    public function iotDevice(): HasOne
    {
        return $this->hasOne(IotDevice::class, 'position_id');
    }
    
    /**
     * Lấy router ở vị trí này (nếu có)
     */
    public function router(): HasOne
    {
        return $this->hasOne(Router::class, 'position_id');
    }
}
