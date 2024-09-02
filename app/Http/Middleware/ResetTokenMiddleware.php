<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetTokenMiddleware
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
                if ($result != 'unauthorized' && $result->type == 'reset_pwd') {

                    $request->headers->set('email', $result->userEmail);

                    return $next($request);
                } else {
                    return redirect('/login');
                }
            } else {
                return redirect('/login');
            }

        } catch (\Throwable $th) {
            return redirect('/login');
        }
    }
}
