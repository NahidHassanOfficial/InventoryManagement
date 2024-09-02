<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        try {
            if ($token) {
                $result = JWTToken::verifyToken($token);
                if ($result != 'unauthorized' && $result->type == 'auth') {
                    return redirect('/dashboard');
                } else {
                    return $next($request);
                }
            } else {
                return $next($request);
            }

        } catch (\Throwable $th) {
            return $next($request);
        }
    }
}
