<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceEvent extends Model
{
    protected $fillable = [
        'device_id', 'type', 'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
