<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLevel
{
    public function handle(Request $request, Closure $next, $level)
    {
        // Cek apakah user memiliki level yang sesuai
        if (auth()->user()->level !== $level) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Insufficient level',
            ], 403);
        }

        return $next($request);
    }
}
