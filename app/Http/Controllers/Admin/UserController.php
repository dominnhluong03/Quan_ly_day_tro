<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // DANH SÁCH TÀI KHOẢN TENANT
    public function index()
    {
        $tenants = User::with(['tenant.contracts'])
            ->where('role', 'tenant')
            ->orderByDesc('id')
            ->get();

        return view('admin.users.index', compact('tenants'));
    }

    // THÊM TÀI KHOẢN
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|in:tenant,admin',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'tenant',
            'status' => 1,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Thêm tài khoản thành công');
    }

    // XÓA TÀI KHOẢN
    public function destroy($id)
    {
        $user = User::with(['tenant.contracts'])
            ->where('id', $id)
            ->where('role', 'tenant')
            ->first();

        if (! $user) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản cần xóa.');
        }

        $tenant = $user->tenant;

        if ($tenant) {
            $hasActiveContract = $tenant->contracts()
                ->where('status', 'active')
                ->exists();

            if ($tenant->status === 'renting' || $hasActiveContract) {
                return redirect()->back()->with(
                    'error',
                    'Không thể xóa tài khoản này vì khách đang thuê nhà hoặc còn hợp đồng hiệu lực.'
                );
            }

            // Nếu muốn xóa luôn hồ sơ tenant khi không còn thuê
            $tenant->delete();
        }

        $user->delete();

        return redirect()->back()->with('success', 'Xóa tài khoản thành công');
    }
}