<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IotDevice extends Model
{
    /** @use HasFactory<\Database\Factories\IotDeviceFactory> */
    use HasFactory;
    
    /**
     * Lấy môi trường chứa thiết bị IoT này
     */
    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }
    
    /**
     * Lấy vị trí của thiết bị IoT này
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Devices_position::class, 'position_id');
    }
}
