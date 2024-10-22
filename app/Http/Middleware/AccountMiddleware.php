<?php

namespace App\Http\Middleware;

use Closure;
// use Auth;
class AccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
    //     return $next($request);
    // }
    public function handle($request, Closure $next, $guard = null)
    {
        if ( \Auth::check() ) {  
            if (\Auth::user()->role_id == 3 || \Auth::user()->role_id == 4 || \Auth::user()->role_id == 2 || \Auth::user()->role_id == 8 ) {
                return $next($request);  
            }
            return back()->with(['message'=> 'Your Permission Denied for go to the link', 'status'=> "warning", 'status_icon'=> ' fa-exclamation-triangle']);
        }
        return redirect('/login');
    }
}
