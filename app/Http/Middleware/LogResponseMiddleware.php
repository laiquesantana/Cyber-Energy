<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogResponseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);


        return $response;
    }
}
