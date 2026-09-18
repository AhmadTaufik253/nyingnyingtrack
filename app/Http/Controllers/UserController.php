<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->withCount('devices')
            ->when($request->search, fn($q) => $q->where('email', 'like', "%{$request->search}%"))
            ->paginate(20);

        $devices = Device::query()
            ->with('customer.user')
            ->when($request->search, fn($q) => $q->where('imei', 'like', "%{$request->search}%"))
            ->paginate(20);

        return view('admin.users', compact('users','devices'));
    }

    public function search()
    {
        $currentUser = Auth::user();
        
        // kalau admin, bisa lihat semua user; kalau bukan, mungkin cuma diri sendiri
        $users = $currentUser->role === 'admin'
            ? User::select('id', 'email', 'name')->orderBy('email')->get()
            : User::select('id', 'email', 'name')->where('id', $currentUser->id)->get();
        
        return response()->json($users);
    }

    public function store(Request $request) {
        // next
    }

    public function toggleActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'is_active' => $user->is_active,
        ]);
    }

    public function devices(User $user)
    {
        return response()->json(
            $user->devices()
                ->select(
                    'devices.id',
                    'devices.name',
                    'devices.imei',
                    // 'devices.is_online',
                    // 'devices.expiration_date'
                )
                ->get()
                ->map(fn ($d) => [
                    'id' => $d->id,
                    'name' => $d->name,
                    'imei' => $d->imei,
                    // 'is_online' => (bool) $d->is_online,
                    // 'expiration_date' => $d->expiration_date ? \Carbon\Carbon::parse($d->expiration_date)->format('Y-m-d H:i:s') : null,
                ])
        );
    }
}
