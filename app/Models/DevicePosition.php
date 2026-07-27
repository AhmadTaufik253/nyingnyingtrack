<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevicePosition extends Model
{
    protected $fillable = [

        'device_id',

        'lat',
        'lng',

        'altitude',

        'speed',

        'course',

        'satellite',

        'position_time',

        'priority',

        'event_id',

        'attributes',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
