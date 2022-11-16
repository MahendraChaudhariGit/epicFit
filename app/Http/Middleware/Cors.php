<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $handle = $next($request);

        if(method_exists($handle, 'header'))
        {
            $handle->header("Access-Control-Expose-Headers", "Access-Control-*")
                ->header("Access-Control-Allow-Headers", "Access-Control-*, Origin, X-Requested-With, Content-Type, Accept")
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, HEAD')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Allow', 'GET, POST, PUT, DELETE, OPTIONS, HEAD');
        }

        return $handle;
    }
}