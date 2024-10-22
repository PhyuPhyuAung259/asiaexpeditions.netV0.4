<?php

namespace App\Http\Middleware;

use Closure;

class MiddleWareCheckGuardName
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
        if (Auth::guard("admin")->check()) {            

            if ( \Auth::user()->company->status == 0 || \Auth::user()->banned == 1 ) {
                return response()->view('errors.expired', ['expired']);
            }
            return $next($request);  
            // }/
            
        }
        // return redirect()->route('login'); 
        // return $next($request);
    }
}
