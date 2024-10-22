<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class IsadminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next, $guard = null)
    // {
        // if (Auth::check()) {
        //     return redirect('login');
        // }else{
            
        //     return $next($request);    
        // }        
        // if (Auth::guard($guard)->check()) {
        //     return redirect('/admin');
        // }
        // return $next($request);
    // }
    public function handle($request, Closure $next, $guard = null)
    {
        if ( Auth::check() ) {            
            return $next($request);  
            // return redirect()->intended("/");
        }
        return redirect('/login'); 
    }
    // public function handle($request, Closure $next, $guard = null)
    // {
    //     if (Auth::guard($guard)->check()) {
    //         return redirect('/admin');
    //     }

    //     return $next($request);
    // }
}
