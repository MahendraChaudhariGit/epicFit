<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthenticateSuperAdmin
 * @package App\Http\Middleware
 */
class AuthenticateSuperAdmin
{
    /**
     * @param  $request
     * @param  callable   $next
     * @param  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isAdminLoggedIn = session()->get('is_admin_logged_in');
        if (!$isAdminLoggedIn) {
            return redirect()->route('superadmin.login');
        }else{
            if(session()->has('adminData')){
                $adminData = session()->get('adminData');
                $accountType = $adminData->account_type;
                if($accountType == 'Super Admin'){
                    return $next($request);
                }else{
                    session()->forget(['is_admin_logged_in','adminData']);
                    return redirect()->route('superadmin.login');
                }
            }else{
               session()->forget(['is_admin_logged_in','adminData']);
               return redirect()->route('superadmin.login'); 
            }
        }
    }
}