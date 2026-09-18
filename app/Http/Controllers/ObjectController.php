<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Device;

class ObjectController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::query()
            ->with('customer.user')
            ->when($request->search, fn($q) => $q->where('imei', 'like', "%{$request->search}%"))
            ->paginate(20);

        $users = User::query()
            ->withCount('devices')
            ->when($request->search, fn($q) => $q->where('email', 'like', "%{$request->search}%"))
            ->paginate(20);
        \Log::info('Devices: ' . json_encode($devices) . ', Users: ' . json_encode($users));
        return view('admin.objects', compact('devices', 'users'));
    }
}
