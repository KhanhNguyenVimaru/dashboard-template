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
}
