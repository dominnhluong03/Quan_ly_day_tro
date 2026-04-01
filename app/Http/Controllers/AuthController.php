<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER (mặc định là TENANT)
    |--------------------------------------------------------------------------
    */
    public function register(Request $request)
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
            'role'     => 'tenant',   // 👈 QUAN TRỌNG
            'status'   => 1
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN → PHÂN QUYỀN
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            $user = Auth::user();

            // 👉 ADMIN
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // 👉 TENANT
            if ($user->role === 'tenant') {
                return redirect()->route('tenant.dashboard');
            }

            // fallback
            Auth::logout();
            return redirect('/login')->withErrors('Tài khoản không hợp lệ');
        }

        return back()->withErrors([
            'email' => 'Sai email hoặc mật khẩu'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // 
    public function changePassword(Request $request)
    {
        $request->validate([

            'old_password' => 'required',

            'new_password' => 'required|min:6|confirmed',

        ]);


        if (!Hash::check($request->old_password, auth()->user()->password)) {

            return back()->with('error','Mật khẩu cũ không đúng');

        }


        auth()->user()->update([

            'password' => Hash::make($request->new_password)

        ]);


        return back()->with('success','Đổi mật khẩu thành công');
    }
}
