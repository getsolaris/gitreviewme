<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Administrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === User::ROLE_ADMINISTRATOR) {
            return $next($request);
        }

        return response()->json([
            'message' => 'You don\'t have permission.',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
