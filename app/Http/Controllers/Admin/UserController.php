<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // HIỂN THỊ DANH SÁCH TENANT
    public function index()
    {
        $tenants = User::where('role', 'tenant')->get();
=======
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tenant;

class UserController extends Controller
{
    // DANH SÁCH TÀI KHOẢN TENANT
    public function index()
    {
        $tenants = User::with(['tenant.contracts'])
            ->where('role', 'tenant')
            ->orderByDesc('id')
            ->get();
>>>>>>> feb1f02 (first commit)

        return view('admin.users.index', compact('tenants'));
    }

<<<<<<< HEAD
    

    // THÊM TENANT
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'tenant',
=======
    // THÊM TÀI KHOẢN
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'nullable|in:tenant,admin',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'] ?? 'tenant',
>>>>>>> feb1f02 (first commit)
            'status'   => 1
        ]);

        return redirect()
<<<<<<< HEAD
        ->route('admin.users.index')
        ->with('success','Thêm tài khoản thành công');
    }

    // XOÁ TENANT
    public function destroy($id)
    {
        User::where('id', $id)
            ->where('role', 'tenant')
            ->delete();

        return redirect()->back()->with('success', 'Xoá tài khoản thành công');
    }
}
=======
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

        if (!$user) {
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
        }

        // Nếu muốn xóa luôn hồ sơ tenant khi không còn thuê
        if ($tenant) {
            $tenant->delete();
        }

        $user->delete();

        return redirect()->back()->with('success', 'Xóa tài khoản thành công');
    }
}
>>>>>>> feb1f02 (first commit)
