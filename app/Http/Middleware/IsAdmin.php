<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        return response()->json(['message' => 'Tidak diizinkan. Hanya admin yang bisa mengakses.'], 403);
    }
}
