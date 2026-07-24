<?php
namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DevicePosition;
use Illuminate\Http\Request;

class DevicePositionController extends Controller
{
    public function receive(Request $req)
    {
        \Log::info('API Received:', $req->all());
        // contoh: alat kirim data via Query Params / JSON
        $imei = $req->input('imei');
        $lat  = $req->input('lat');
        $lng  = $req->input('lng');
        $speed = $req->input('speed', 0);
        $course = $req->input('course', 0);

        if (!$imei || !$lat || !$lng) {
            return response()->json(['error' => 'Invalid data'], 422);
        }

        // cari device berdasarkan IMEI
        $device = Device::where('imei', $imei)->first();
        \Log::info('Device found:', ['device' => $device ? $device->toArray() : null]);
        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        // save posisi
        DevicePosition::create([
            'device_id' => $device->id,
            'lat' => $lat,
            'lng' => $lng,
            'speed' => $speed,
            'course' => $course,
            'satellite' => $req->input('satellite', null),
            'position_time' => now(),
        ]);

        return response()->json(['status' => 'OK']);
    }
}
