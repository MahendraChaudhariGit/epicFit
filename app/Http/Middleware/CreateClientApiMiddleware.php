<?php
namespace App\Http\Middleware;

use Closure;
use App\Business;
use Request;

class CreateClientApiMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(!$request->has('accessPass')) //Check if API key is provided or not
            return json_encode(['code'=>'401', 'message'=>'Please provide your access pass.']);

        // $business = Business::where('api_key', md5($request->accessPass))->select('id')->first();
             $business = Business::where('api_key', $request->accessPass)->select('id')->first();
        if(!$business) //Check if provided API key is valid
            return json_encode(['code'=>'401', 'message'=>'Invalid access. Please check your access pass.']);

        $request->attributes->add(['businessId' => $business->id]);
        return $next($request);
    }
}
