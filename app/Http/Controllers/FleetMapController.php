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
            $query->whereHas('customer', function($q) use ($user){
                $q->where('user_id', $user->id);
            });
        }

        $devices = $query->get();

        $data = $devices->map(function($d){
            $lastPos = $d->latestPosition;
            return [
                'id' => $d->id,
                'name' => $d->name,
                'imei' => $d->imei,
                'model' => $d->model,
                'lat' => $lastPos->lat ?? null,
                'lng' => $lastPos->lng ?? null,
                'speed' => $lastPos->speed ?? 0,
                'updated_at' => $lastPos->device_time ?? null
            ];
        });

        return response()->json($data);
    }

    public function deviceHistory($id)
    {
        $user = Auth::user();
        
        $device = Device::with('customer')->findOrFail($id);
        
        // Authorization check
        if ($user->role !== 'admin' && $device->customer->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $positions = $device->positions()
            ->orderBy('position_time', 'asc')
            ->get(['lat', 'lng', 'position_time']);

        $data = $positions->map(function($p){
            return [
                'lat' => $p->lat,
                'lng' => $p->lng,
                'time' => $p->position_time,
            ];
        });

        return response()->json($data);
    }

}
