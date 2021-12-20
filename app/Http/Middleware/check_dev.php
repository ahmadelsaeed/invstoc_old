<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class check_dev
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
        $user=Auth::user();
        if(isset($user) && $user->user_type=="dev"){
            return $next($request);
        }
        return redirect("/");
    }
}
