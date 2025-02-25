<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user(); // Ambil user yang sedang login
        
        if (!$user || $user->role !== 'admin') { // Pastikan role benar
            return response()->json(['message' => 'Unauthorized', 'note' => 'Only admin can access this route'], 403);
        }
        
        return $next($request);
    }
}
