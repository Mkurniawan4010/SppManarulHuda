<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$levels
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$levels)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        if (in_array($user->level, $levels)) {
            return $next($request);
        }
        
        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}