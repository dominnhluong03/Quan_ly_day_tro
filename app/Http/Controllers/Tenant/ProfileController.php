<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();

        return view('tenant.profile.index', compact('user', 'tenant'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $tenant = Tenant::firstOrCreate(
            ['user_id' => $user->id],
            ['status' => 'free']
        );

        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'phone'      => 'nullable|string|max:15',
            'gender'     => 'nullable|in:male,female,other',
            'birthday'   => 'nullable|date',
            'job'        => 'nullable|string|max:100',
            'hometown'   => 'nullable|string|max:255',
            'cccd_front' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'cccd_back'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $user->update([
            'name' => $data['name'],
        ]);

        if ($request->hasFile('cccd_front')) {
            if ($tenant->cccd_front && Storage::disk('public')->exists($tenant->cccd_front)) {
                Storage::disk('public')->delete($tenant->cccd_front);
            }

            $data['cccd_front'] = $request->file('cccd_front')->store('tenants/cccd', 'public');
        }

        if ($request->hasFile('cccd_back')) {
            if ($tenant->cccd_back && Storage::disk('public')->exists($tenant->cccd_back)) {
                Storage::disk('public')->delete($tenant->cccd_back);
            }

            $data['cccd_back'] = $request->file('cccd_back')->store('tenants/cccd', 'public');
        }

        $tenant->update([
            'phone'      => $data['phone'] ?? $tenant->phone,
            'gender'     => $data['gender'] ?? $tenant->gender,
            'birthday'   => $data['birthday'] ?? $tenant->birthday,
            'job'        => $data['job'] ?? $tenant->job,
            'hometown'   => $data['hometown'] ?? $tenant->hometown,
            'cccd_front' => $data['cccd_front'] ?? $tenant->cccd_front,
            'cccd_back'  => $data['cccd_back'] ?? $tenant->cccd_back,
        ]);

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }
}