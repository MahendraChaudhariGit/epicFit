<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Access\User\User;

class LoginController extends Controller
{

    public function showLoginForm(){
        if(session()->has('is_admin_logged_in') && session()->has('adminData')){
            return redirect()->route('superadmin.dashboard');
        }
        return view('super-admin.auth.login');
    }
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::whereEmail($credentials['email'])->whereAccountType('Super Admin')->first();
        if($user){
            if(\Hash::check($credentials['password'], $user->password)){
                session(['is_admin_logged_in' => true, 'adminData' => $user]);
                return redirect()->route('superadmin.dashboard');
            }else{
                return redirect()->back()->withErrors('Invalid E-mail/Password');
            }
        }else{
            return redirect()->back()->withErrors('Credentials did not match');
        }
    }

    public function logout(){
        session()->forget(['is_admin_logged_in','adminData']);
        return redirect()->route('superadmin.login');
    }
}