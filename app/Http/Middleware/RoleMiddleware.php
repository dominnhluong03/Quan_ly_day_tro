<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Chưa đăng nhập
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Sai quyền
        if (auth()->user()->role !== $role) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        return $next($request);
    }
}
