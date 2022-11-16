<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use Session;
use App\User;
use App\Business;
use Auth;
use DB;

class UserController extends Controller {

  /**
   * Login blade
   * @param Business id
   * @return view login
   */
  public function index($businessId = '') {
      (int)$businessId;
      return view('Result.login', compact('businessId', $businessId));
  }


  /**
   * Login
   * If the class is using the ThrottlesLogins trait, we can automatically throttle
   * the login attempts for this application. We'll key this by the username and
   * the IP address of the client making these requests into this application.
   * @param Request
   * @return new-dashboard
  **/
  public function login(Request $request) { 
    // dd($request->all());
    $throttles = in_array(
      ThrottlesLogins::class, class_uses_recursive(get_class($this))
    );

    if ($throttles && $this->hasTooManyLoginAttempts($request)) {
        return $this->sendLockoutResponse($request);
    }

    $loginData = array('email' => $request->uname, 'password' => $request->password, 'account_type' => 'Client', 'business_id' => $request->businessId, 'source' => $request->source);

    if($request->has('source') && $request['source'] == 'epicfitstudio') {
      Auth::loginUsingId($request->userId);
      return $this->handleUserWasAuthenticated($request, $throttles);
    } else if (Auth::attempt($loginData, $request->has('remember'))) {
        return $this->handleUserWasAuthenticated($request, $throttles);
    }

    return redirect()->back()->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([$this->loginUsername() => trans('auth.failed')]);
  }


  /**
   * @param Request $request
   * @param $throttles
   * @return \Illuminate\Http\RedirectResponse
   * @throws GeneralException
   */
  protected function handleUserWasAuthenticated(Request $request, $throttles) {
    if ($throttles) {
        $this->clearLoginAttempts($request);
    }

    /* Check to see if the users account is confirmed and active */
    if (Auth::user()->confirmed == 0) {
        Auth::logout();
        if($request->has('source') && $request['source'] == 'epicfitstudio')
          return json_encode(['code' => '301', 'message' => 'Your account is not confirmed.']);
        else
          return redirect()->back()->withErrors([$this->loginUsername() => 'Your account is not confirmed. ']);
    } else {
      Session::put('userType', Auth::user()->account_type);
      $business = Business::find(Auth::user()->business_id);
      if ($business) {
        Session::put('businessId', $business->id);
        if ($business->locations()->exists())
            Session::put('ifBussHasLocations', true);

        if ($business->staffs()->exists())
            Session::put('ifBussHasStaffs', true);

        if ($business->services()->exists())
            Session::put('ifBussHasServices', true);

        if ($business->classes()->exists())
            Session::put('ifBussHasClasses', true);

        if ($business->products()->exists())
            Session::put('ifBussHasProducts', true);

        if ($business->clients()->exists())
            Session::put('ifBussHasClients', true);

        if ($business->contacts()->exists())
            Session::put('ifBussHasContacts', true);
      }

      //return redirect()->intended('profile/edit');
      if($request->has('source') && $request['source'] == 'epicfitstudio')
        return json_encode(['code' => '201', 'message' => 'Login successful']);
      else 
        return redirect()->intended('new-dashboard');
    }
  }


  /**
   * This is here so we can use the default Laravel ThrottlesLogins trait
   * @return string
   */
  public function loginUsername() {
      return 'uname';
  }


  /**
   * @return logout
   */
  public function logout() {
    if(Session::has('businessId'))
      $url = 'login/'.Session::get('businessId');
    else
      $url = 'login/';

    Auth::logout();
    Session::flush();
    return redirect($url);
  }


  /**
   * @return register 
   */
  /*public function register($businessId = ''){
    return view('register', compact('businessId', $businessId));
  }*/

}
