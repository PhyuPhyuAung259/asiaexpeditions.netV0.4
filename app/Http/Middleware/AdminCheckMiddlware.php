<?php

namespace App\Http\Middleware;

use Closure;

class AdminCheckMiddlware
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
        // return $next($request);
        if ( \Auth::check() ) {  
            if ( \Auth::user()->role_id == 2) {
                return $next($request);  
            }
            // return view('admin.user.message');
            return redirect()->intended('');
        }
        return redirect('/login');
    }
}
