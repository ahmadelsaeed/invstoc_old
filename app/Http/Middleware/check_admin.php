<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class check_admin
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
        if(isset($user) && $user->user_active == 1 && $user->user_can_login == 1 &&($user->user_type=="admin"||$user->user_type=="dev")){
            return $next($request);
        }

        return redirect("/");
    }
}
