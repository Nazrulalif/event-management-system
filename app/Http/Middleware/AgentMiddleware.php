<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the agent is logged in using the agent guard
        if (Auth::guard('agent')->check()) {
            return $next($request);
        } elseif (Auth::check()) {
            if (Auth::user()->role_guid == 1) {
                return redirect('/admin/dashboard')->with('message', 'error');
            } else {
                return redirect('/home')->with('message', 'error');
            }
        } else {
            return redirect('/')->with('message', 'error');
        }
        return $next($request);
    }
}
