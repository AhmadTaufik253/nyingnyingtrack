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
        'sim_number',
        'firmware',
        'is_active',

        'last_seen_at',
        'last_ip',
        'last_port',

        'battery',
        'voltage',
        'gsm_signal',

        'ignition',
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
        return $this->hasOne(DevicePosition::class)->latestOfMany();
    }

    public function events()
    {
        return $this->hasMany(DeviceEvent::class);
    }
}
