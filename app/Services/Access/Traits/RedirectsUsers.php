<?php

namespace App\Services\Access\Traits;

use Session;
use Illuminate\Support\Facades\Auth;
use App\Permissions;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath(){
        $user = Auth::user();
      //  dd($user->hasPermission($user,'view-backend'));
        /*if($user->hasPermission($user,'view-backend')){
               return route('dashboard.show'); 
        }
           //return 'dashboard';
           // return 'dashboard/calendar';
        else {
                           return route('dashboard.show'); 

             //return route('calendar-new');
         }*/
         return route('dashboard.show'); 


        //dd(Auth::user()->hasPermission(Auth::user(), 'add-users'));
        //Auth::user()->hasPermission(Auth::user(), 'add-users');
       
        /*if(Auth::user()->hasPerm('view-backend'))
            dd('true');
        else
            dd('false');
        //if(Session::get('userType') == 'Administrator' && !Auth::user()->is_custom_perm)
            //dd('ok');

    	//dd(Auth::user());
    	/*if((!Auth::user()->is_custom_perm && Session::get('userType') == 'Administrator') || (Auth::user()->is_custom_perm && ))
    		return '/dashboard/calendar';
    	else
    		return '';*/
        
        //return property_exists($this, 'redirectTo') ? $this->redirectTo : '/dashboard/calendar';
    }	
}