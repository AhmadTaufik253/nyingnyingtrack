<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevicePosition extends Model
{
    protected $fillable = [

        'device_id',

        'latitude',
        'longitude',

        'altitude',
        'angle',

        'speed',

        'satellites',

        'priority',

        'event_id',

        'gps_time',

        'attributes',
    ];

    protected $casts = [

        'gps_time' => 'datetime',

        'attributes' => 'array',

        'latitude' => 'float',
        'longitude' => 'float',

        'speed' => 'float',

        'altitude' => 'integer',
        'angle' => 'integer',

        'satellites' => 'integer',

        'priority' => 'integer',

        'event_id' => 'integer',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}