<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userType): Response
    {
        $userTypeMap = [
            'user' => 0,
            'admin' => 1,
            'manager' => 2
        ];

        if (auth()->user()->getRawOriginal('type') == $userTypeMap[$userType]) {
            return $next($request);
        }
          
        return response()->json(['You do not have permission to access for this page.']);
    }
}
