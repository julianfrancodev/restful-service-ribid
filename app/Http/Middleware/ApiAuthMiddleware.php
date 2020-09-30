<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
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
        // TODO valide if user auth
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $cleanToken = str_replace("\"",'',$token);
        $checkToken = $jwtAuth->checkToken($cleanToken);

        if($checkToken){
            return $next($request);
        }else{

            $data = array(
                'code'=> 400,
                'status'=>'error',
                'message'=> 'Usuario no identificado'
            );

            return response()->json($data,$data['code']);
        }

    }
}
