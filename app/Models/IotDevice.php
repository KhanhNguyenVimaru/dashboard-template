<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotDevice extends Model
{
    /** @use HasFactory<\Database\Factories\IotDeviceFactory> */
    use HasFactory;

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
    ];
    /**
     * Scope a query to search IoT devices by keyword across multiple fields.
     */

    /**
     * Scope a query to search IoT devices by keyword across fillable fields.
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('device_name', 'like', "%$keyword%")
                ->orWhere('device_type', 'like', "%$keyword%")
                ->orWhere('serial_number', 'like', "%$keyword%")
                ->orWhere('ip_address', 'like', "%$keyword%")
                ->orWhere('port', 'like', "%$keyword%")
                ->orWhere('mac_address', 'like', "%$keyword%")
                ->orWhere('manufacturer', 'like', "%$keyword%")
                ->orWhere('model', 'like', "%$keyword%")
                ->orWhere('config', 'like', "%$keyword%")
                ->orWhere('is_active', 'like', "%$keyword%")
                ->orWhere('coverage', 'like', "%$keyword%")
                ->orWhere('protocol', 'like', "%$keyword%")
                ->orWhere('firmware_version', 'like', "%$keyword%")
                ->orWhere('last_seen', 'like', "%$keyword%")
                ->orWhere('battery_level', 'like', "%$keyword%")
                ->orWhere('rssi', 'like', "%$keyword%");
        });
    }
}
