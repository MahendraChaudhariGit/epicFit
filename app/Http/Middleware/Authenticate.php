<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Events\Frontend\Auth\UserLoggedIn;
use Session;
use Config;
use Cookie;
use DB;
use Illuminate\Contracts\Session\Session as SessionSession;

//use Carbon\Carbon;

/**
 * Class Authenticate
 * @package App\Http\Middleware
 */
class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        
        // $userDetails = DB::table('epicfitstudio_login')
        // ->get();
        // dd($userDetails);
        //Config::set('app.timezone', 'Australia/Sydney');
        /*config(['app.timezone' => 'America/Chicago']);
        date_default_timezone_set('America/Chicago');*/       
        if (Auth::guard($guard)->guest()) {
            if(session()->has('is_admin_logged_in') && session()->get('is_admin_logged_in') == true && session()->has('adminData') && session()->get('adminData') && (strpos($request->getRequestUri(), 'pipeline-process') !== false || strpos($request->getRequestUri(), 'gallery') !== false)){
                Auth::loginUsingId(334);
                Session(['businessId' => 55,'ifBussHasLocations'=>true,'ifBussHasAreas'=>true,'ifBussHasStaffs'=>true,'ifBussHasServices'=>true,'ifBussHasClasses'=>true,'ifBussHasProducts'>true,'ifBussHasClients'=>true,'ifBussHasContacts'=>true,'ifBussHasSalesToolsDiscounts'=>true,'ifBussHasResources'=>true,'ifBussHasAdministrators'=>true]);

                return $next($request);
                
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }
        if(!Session::has('businessId')){
            if (Auth::viaRemember()) {
                 //login via remember me action
                //return $this->handleUserWasAuthenticated($request, $throttles);
                event(new UserLoggedIn(access()->user()));
                //dd('yes remember me');
            }
            //dd('yes');
        }
        //dd('no');
        /*if(Session::has('timeZone')){
            \Config::set('app.timezone', Session::get('timeZone'));
            date_default_timezone_set(Session::get('timeZone'));
        }*/

        return $next($request);
    }
}