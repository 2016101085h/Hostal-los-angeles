<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
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
        try {
           $user = JWTAuth::parseToken()->authenticate();
           if($user->rol_id === 1) {
               $user->rol = 'Administrador';
           }
        } catch (Exception $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    'status' => false,
                    'msg ' => 'Token expirado'
                ]);
            }else{
                if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json([
                        'status'=> false,
                        'msg ' => 'Token invalido'
                    ]);
                }else{
                    return response()->json([
                        'status'=> false,
                        'msg ' => 'Token es requerido'
                    ]);
                }
            }
        }
        return $next($request->merge(['user' => $user]));
    }
}
