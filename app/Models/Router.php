<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Router extends Model
{
    /** @use HasFactory<\Database\Factories\RouterFactory> */
    use HasFactory;

    /**
     * Lấy môi trường chứa router này
     */
    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }

    /**
     * Lấy vị trí của router này
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Devices_position::class, 'position_id');
    }

    protected $fillable = [
        'mac_address',
        'name',
        'port',
        'ip_address',
        'location',
        'model',
        'manufacturer',
        'firmware_version',
        'status',
        'bandwidth',
        'coverage',
    ];

    /**
     * Scope a query to search routers by keyword across multiple fields.
     */
    public function scopeSearch($query, $keyword){
        return $query->where(function ($q) use ($keyword) {
            $q->where('mac_address', 'like', "%$keyword%")
                ->orWhere('name', 'like', "%$keyword%")
                ->orWhere('port', 'like', "%$keyword%")
                ->orWhere('ip_address', 'like', "%$keyword%")
                ->orWhere('location', 'like', "%$keyword%")
                ->orWhere('model', 'like', "%$keyword%")
                ->orWhere('manufacturer', 'like', "%$keyword%")
                ->orWhere('firmware_version', 'like', "%$keyword%")
                ->orWhere('status', 'like', "%$keyword%")
                ->orWhere('bandwidth', 'like', "%$keyword%")
                ->orWhere('coverage', 'like', "%$keyword%");
        });
    }
}
