<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [

        'customer_id',

        'imei',
        'name',
        'model',
        'protocol',
        'firmware',

        'sim_number',
        'phone_number',

        'is_active',
        'is_online',

        'last_ip',
        'last_seen',

        'last_latitude',
        'last_longitude',
        'last_altitude',
        'last_speed',
        'last_course',
        'last_satellites',

        'battery',
        'voltage',
        'gsm_signal',
        'ignition',

        'last_position_time',
    ];

    protected $casts = [

        'is_active' => 'boolean',
        'is_online' => 'boolean',
        'ignition' => 'boolean',

        'battery' => 'float',
        'voltage' => 'float',

        'last_latitude' => 'float',
        'last_longitude' => 'float',
        'last_speed' => 'float',

        'last_seen' => 'datetime',
        'last_position_time' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function positions()
    {
        return $this->hasMany(DevicePosition::class);
    }

    public function latestPosition()
    {
        return $this->hasOne(DevicePosition::class)
                    ->latest('gps_time');
    }

    public function events()
    {
        return $this->hasMany(DeviceEvent::class);
    }
}
