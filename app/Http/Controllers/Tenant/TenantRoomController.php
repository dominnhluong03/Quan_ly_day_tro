<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Contract;
use App\Models\Room;

class TenantRoomController extends Controller
{
    public function index()
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();

        // Lấy hợp đồng gần nhất của tenant để xác định tòa nhà
        $latestContract = Contract::with('room.building')
            ->where('tenant_id', $tenant->id)
            ->orderByDesc('id')
            ->first();

        if (!$latestContract || !$latestContract->room || !$latestContract->room->building_id) {
            return view('tenant.rooms.index', [
                'rooms' => collect(),
                'building' => null,
            ]);
        }

        $buildingId = $latestContract->room->building_id;

        $rooms = Room::with(['images', 'building', 'assets'])
            ->where('building_id', $buildingId)
            ->orderBy('room_code')
            ->get();

        return view('tenant.rooms.index', [
            'rooms' => $rooms,
            'building' => $latestContract->room->building,
        ]);
    }
}