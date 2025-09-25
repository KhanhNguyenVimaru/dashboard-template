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

    #hàm này để chỉ định các trường có thể gán hàng loạt (mass assignable)
    protected $fillable = [
        'device_name',
        'device_type',
        'serial_number',
        'ip_address',
        'port',
        'mac_address',
        'manufacturer',
        'model',
        'config',
        'is_active',
        'coverage',
        'protocol',
        'firmware_version',
        'last_seen',
        'battery_level',
        'rssi',
        'environment_id', // Thêm trường environment_id vào fillable
        'position_id',    // Thêm trường position_id vào fillable
    ];
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
