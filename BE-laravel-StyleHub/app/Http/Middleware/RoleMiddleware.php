<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // Chưa đăng nhập → redirect về login
            return redirect()->route('login');
        }

        // Nếu role user không nằm trong mảng roles cho phép → redirect về login
        if (!in_array(Auth::user()->role, $roles)) {
            return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập');
        }

        return $next($request);
    }
}
