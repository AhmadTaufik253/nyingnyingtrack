<?php
namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DevicePosition;
use Illuminate\Http\Request;

class DevicePositionController extends Controller
{
    // public function receive(Request $req)
    // {
    //     \Log::info('API Received:', $req->all());
    //     // contoh: alat kirim data via Query Params / JSON
    //     $imei = $req->input('imei');
    //     $lat  = $req->input('lat');
    //     $lng  = $req->input('lng');
    //     $speed = $req->input('speed', 0);
    //     $course = $req->input('course', 0);

    //     if (!$imei || !$lat || !$lng) {
    //         return response()->json(['error' => 'Invalid data'], 422);
    //     }

    //     // cari device berdasarkan IMEI
    //     $device = Device::where('imei', $imei)->first();
    //     \Log::info('Device found:', ['device' => $device ? $device->toArray() : null]);
    //     if (!$device) {
    //         return response()->json(['error' => 'Device not found'], 404);
    //     }

    //     // save posisi
    //     DevicePosition::create([
    //         'device_id' => $device->id,
    //         'lat' => $lat,
    //         'lng' => $lng,
    //         'speed' => $speed,
    //         'course' => $course,
    //         'satellite' => $req->input('satellite', null),
    //         'position_time' => now(),
    //     ]);

    //     return response()->json(['status' => 'OK']);
    // }
    public function receive(Request $request)
    {
        $request->validate([
            'imei' => 'required|string',
            'records' => 'required|array|min:1',

            'records.*.latitude' => 'required|numeric',
            'records.*.longitude' => 'required|numeric',
            'records.*.position_time' => 'required|date',

            'records.*.speed' => 'nullable|numeric',
            'records.*.altitude' => 'nullable|integer',
            'records.*.course' => 'nullable|integer',
            'records.*.satellite' => 'nullable|integer',
            'records.*.priority' => 'nullable|integer',
            'records.*.event_id' => 'nullable|integer',
            'records.*.attributes' => 'nullable|array',
        ]);

        $device = Device::where('imei', $request->imei)->first();

        if (!$device) {
            return response()->json([
                'message' => 'Device not found'
            ], 404);
        }

        foreach ($request->records as $record) {

            $position = DevicePosition::create([

                'device_id' => $device->id,

                'latitude' => $record['latitude'],
                'longitude' => $record['longitude'],

                'altitude' => $record['altitude'] ?? 0,

                'angle' => $record['course'] ?? 0,

                'speed' => $record['speed'] ?? 0,

                'satellites' => $record['satellite'] ?? 0,

                'priority' => $record['priority'] ?? null,

                'event_id' => $record['event_id'] ?? null,

                'gps_time' => $record['position_time'],

                'attributes' => $record['attributes'] ?? [],
            ]);

            // update posisi terakhir device
            $device->update([

                'is_online' => true,

                'last_seen' => now(),

                'last_latitude' => $position->latitude,

                'last_longitude' => $position->longitude,

                'last_altitude' => $position->altitude,

                'last_speed' => $position->speed,

                'last_course' => $position->angle,

                'last_satellites' => $position->satellites,

                'last_position_time' => $position->gps_time,
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }
}
