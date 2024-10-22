<?php

namespace App\Http\Middleware;

use Closure;

class AuthKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $apitoken = $request->header('token');
        // if($apitoken != "ABCDEF12345" ){
        //     return response()->json(['message'=>"You don't have promission..!"], 401);
        // }
        return $next($request);
    }
}
