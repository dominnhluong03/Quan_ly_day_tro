<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Room;

class TenantRoomController extends Controller
{
    public function index()
    {
        $tenant = Tenant::where('user_id', auth()->id())->first();

        $rooms = Room::with(['images', 'building', 'assets'])
            ->orderBy('building_id')
            ->orderBy('room_code')
            ->get();

        return view('tenant.rooms.index', [
            'rooms' => $rooms,
            'building' => null,
            'tenant' => $tenant,
        ]);
    }
}