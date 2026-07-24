<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'customer_id', 'imei', 'name', 'model', 'sim_number', 'is_active'
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
