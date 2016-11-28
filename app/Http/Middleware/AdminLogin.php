<?php

namespace App\Http\Middleware;

use Closure;

class AdminLogin
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
        if(!session('user')){
            echo '<script type="text/javascript">';
            echo "parent.location.href='/admin/login'";
            echo '</script>';
            exit;
        }
        return $next($request);
    }
}
