<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        // dd($request->user()->role);
        if(!$request->user()||$request->user()->role!=='admin'){
            return response()->json([
                'message'=>'adminOnly'
            ], 403);
        }
        return $next($request);
    }
}
