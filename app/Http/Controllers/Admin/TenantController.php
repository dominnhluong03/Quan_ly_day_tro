<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Tenant;
use App\Models\User;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('user')
            ->orderByDesc('id')
            ->get();

        // chỉ lấy user role tenant và chưa gắn tenants.user_id
        $users = User::where('role', 'tenant')
            ->whereDoesntHave('tenant')
            ->orderByDesc('id')
            ->get();

        return view('admin.tenants.index', compact('tenants', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'     => 'required|exists:users,id|unique:tenants,user_id',
            'phone'       => 'required|string|max:15',
            'gender'      => 'nullable|in:male,female,other',
            'birthday'    => 'nullable|date',
            'job'         => 'nullable|string|max:100',
            'hometown'    => 'nullable|string|max:255',
            'cccd_front'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'cccd_back'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data['status'] = 'free';

        // upload CCCD -> storage/app/public/tenants/cccd
        if ($request->hasFile('cccd_front')) {
            $data['cccd_front'] = $request->file('cccd_front')->store('tenants/cccd', 'public');
        }
        if ($request->hasFile('cccd_back')) {
            $data['cccd_back'] = $request->file('cccd_back')->store('tenants/cccd', 'public');
        }

        Tenant::create($data);

        return back()->with('success', 'Đã thêm khách thuê thành công!');
    }

    public function edit(Tenant $tenant)
    {
        $tenant->load('user');

        // cho phép đổi user nếu bạn muốn (nhưng thường nên khóa)
        // nếu muốn khóa luôn thì bỏ users này đi
        $users = User::where('role', 'tenant')
            ->orderByDesc('id')
            ->get();

        return view('admin.tenants.edit', compact('tenant', 'users'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'phone'       => 'required|string|max:15',
            'gender'      => 'nullable|in:male,female,other',
            'birthday'    => 'nullable|date',
            'job'         => 'nullable|string|max:100',
            'hometown'    => 'nullable|string|max:255',
            'status'      => 'nullable|in:free,renting',
            'cccd_front'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'cccd_back'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // upload mới thì xóa file cũ
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

        $tenant->update($data);

        return redirect()->route('admin.tenants.index')->with('success', 'Đã cập nhật khách thuê!');
    }

    public function destroy(Tenant $tenant)
    {
        // giống UI: đang thuê thì không cho xóa
        if ($tenant->status === 'renting') {
            return back()->with('success', 'Khách đang thuê phòng, không thể xóa!');
        }

        // xóa file CCCD
        if ($tenant->cccd_front && Storage::disk('public')->exists($tenant->cccd_front)) {
            Storage::disk('public')->delete($tenant->cccd_front);
        }
        if ($tenant->cccd_back && Storage::disk('public')->exists($tenant->cccd_back)) {
            Storage::disk('public')->delete($tenant->cccd_back);
        }

        $tenant->delete();

        return back()->with('success', 'Đã xóa khách thuê!');
    }
}