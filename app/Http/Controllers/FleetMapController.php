<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class FleetMapController extends Controller
{
    public function index()
    {
        return view('fleet.map');
    }

    public function devices()
    {
        $user = Auth::user();

        $query = Device::with(['customer', 'latestPosition']);

        if ($user->role !== 'admin') {
            $query->whereHas('customer', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $devices = $query->get();

        $data = $devices->map(function ($device) {

            $last = $device->latestPosition;

            // return [

            //     'id' => $device->id,
            //     'name' => $device->name,
            //     'imei' => $device->imei,
            //     'model' => $device->model,

            //     'latitude' => $last?->latitude,
            //     'longitude' => $last?->longitude,

            //     'speed' => $last?->speed ?? 0,
            //     'angle' => $last?->angle ?? 0,
            //     'satellites' => $last?->satellites ?? 0,

            //     'battery' => $device->battery,
            //     'voltage' => $device->voltage,
            //     'gsm_signal' => $device->gsm_signal,
            //     'ignition' => $device->ignition,

            //     'gps_time' => $last?->gps_time,
            //     'online' => $device->is_online,

            // ];
            return [

                'id' => $device->id,
                'name' => $device->name,
                'imei' => $device->imei,
                'model' => $device->model,

                'latitude' => $last?->latitude,
                'longitude' => $last?->longitude,

                'speed' => $last?->speed,
                'altitude' => $last?->altitude,
                'angle' => $last?->angle,
                'satellites' => $last?->satellites,

                'battery' => $device->battery,
                'voltage' => $device->voltage,
                'gsm_signal' => $device->gsm_signal,
                'ignition' => $device->ignition,

                'gps_time' => $last?->gps_time,

                'online' => $device->is_online,

            ];
        });

        return response()->json($data);
    }

    public function deviceHistory($id)
    {
        $user = Auth::user();

        $device = Device::with('customer')->findOrFail($id);

        if (
            $user->role !== 'admin' &&
            $device->customer->user_id != $user->id
        ) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 403);
        }

        $positions = $device->positions()
            ->orderBy('gps_time')
            ->get([
                'latitude',
                'longitude',
                'speed',
                'angle',
                'gps_time'
            ]);

        return response()->json(

            $positions->map(function ($position) {

                return [

                    'latitude' => $position->latitude,
                    'longitude' => $position->longitude,
                    'speed' => $position->speed,
                    'angle' => $position->angle,
                    'time' => $position->gps_time,

                ];

            })

        );
    }

}
