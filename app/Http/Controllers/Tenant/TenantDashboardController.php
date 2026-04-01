<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant; // ✅ THÊM DÒNG NÀY
use Illuminate\Http\Request;

class TenantDashboardController extends Controller
{
    public function index()
    {
        $tenant = Tenant::with([
            'user',
            'contracts.room.building',
            'contracts.room.images',
            'contracts.room.contracts.tenant.user'
        ])
        ->where('user_id', auth()->id())
        ->first();

        return view('tenant.dashboard', compact('tenant'));
    }
}
