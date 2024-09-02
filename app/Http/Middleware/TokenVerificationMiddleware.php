<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
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
                if ($result != 'unauthorized') {
                    // header('email: ' . $result->userEmail);
                    // header('id: ' . $result->userId);

                    $request->headers->set('email', $result->userEmail);
                    $request->headers->set('id', $result->userId);

                    view()->share('id', $result->userId);

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
