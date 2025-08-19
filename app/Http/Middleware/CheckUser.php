<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;
class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::get('uid')== "pGI2FmUWdAakrR0oZL2G1H1TV9p2" || Session::get('uid')=="vfi5axXhfod7WNOd6Ia4WK7hgbq1"){
            return $next($request);
        }
    
        abort(403);
    }
}
