<?php
namespace App\Services\Access\Traits;

use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Events\Frontend\Auth\UserLoggedIn;
use App\Events\Frontend\Auth\UserLoggedOut;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Requests\Frontend\Auth\LoginRequest;
use DB;
use App\Business;
use App\Models\Access\User\User;
use Auth;
use Hash;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Auth;
use Session;
//use App\Business;

/**
 * Class AuthenticatesUsers
 * @package App\Services\Access\Traits
 */
trait AuthenticatesUsers{
    use RedirectsUsers;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm($businessUrl = ''){
        return view('frontend.auth.login', compact('businessUrl', $businessUrl))
            ->withSocialiteLinks($this->getSocialLinks());
    }

    /**
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request){
        // dd($request->all());
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        /*if (auth()->attempt($request->only($this->loginUsername(), 'password'), $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }*/
        // $loginData = ['email' => $request->uname, 'password' => $request->password];

       // $loginData['business_id'] = $request->businessId;
        //$loginData['account_type'] = $request->userType;
        
        if($request->businessId)
            $loginData['business_id'] = $request->businessId;
        else
            $loginData['web_url'] = $request->businessUrl;
        $loginData['account_type'] = $request->userType;
        
        if($request->businessUrl == ''){
            // $loginData = array('email' => $request->uname, 'password' => $request->password, 'account_type' => 'Admin');
                $loginData = array('email' => $request->uname, 'password' => $request->password);
            $users = User::select('id','password')
                         ->where('email',$request->uname)
                         ->where('business_id','!=',0)
                         ->where('account_type','Admin')
                         ->get(); 

            $id = [];
            foreach($users as $key => $user){
                if (Hash::check($request->password, $user->password)) {
                    $id[$key] = $user->id;
                }
            }
            
            $user_data = User::with('businesParent')->select('id','account_type','business_id','web_url')->whereIn('id',$id)->get();
             if($user_data){
                if(count($user_data->toArray()) > 1){
                    return view("frontend.auth.login", compact('user_data'));
                }else {
                    if($user_data[0]['business_id'] != 0){
                        $loginData['business_id'] = $user_data[0]['business_id'];      
                    } else{
                        $loginData['web_url'] = $user_data[0]['web_url'];
                    }
                    $loginData['account_type'] = $user_data[0]['account_type'];
                 } 
               } 
         
            // if(count($user_data->toArray()) > 1){
            //     return view("frontend.auth.login", compact('user_data'));
            // }         
            
        }
        else{
            $businessId = Business::where('cp_web_url',$request->businessUrl)->pluck('id')->first();
            $loginData = array('email' => $request->uname, 'password' => $request->password, 'account_type' => 'Admin', 'business_id' => $businessId);
        }
        // if($request->businessId)
        //     $loginData['business_id'] = $request->businessId;
        // else
        //     $loginData['web_url'] = $request->businessUrl;
        // $loginData['account_type'] = $request->userType;
      
		if (Auth::attempt($loginData, $request->has('remember'))) {
            
            //dd(DB::getQueryLog());
            return $this->handleUserWasAuthenticated($request, $throttles);
        }
        /*if (Auth::attempt(['email' => $request->uname, 'password' => $request->password], $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }*/
        /*if (Auth::viaRemember()) {
             //login via remember me action
            //return $this->handleUserWasAuthenticated($request, $throttles);
            event(new UserLoggedIn(access()->user()));
        }*/
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }
        
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        if(session()->has('is_admin_logged_in') && session()->has('adminData')){
            $isAdminLoogedIn = session()->get('is_admin_logged_in');
            $adminData = session()->get('adminData');
        }
        /**
         * Remove the socialite session variable if exists
         */
        if (app('session')->has(config('access.socialite_session_name'))) {
            app('session')->forget(config('access.socialite_session_name'));
        }
        
        /**
         * Remove lock screen session value 
         */
        if(Session::has('lockstatus'))
            Session::forget('lockstatus');

        /**
         * Remove hostname
         * it is use only check result/crm
         */
        if(Session::has('hostname'))
            Session::forget('hostname');

        $slug = Business::where('id',Session::get('businessId'))->pluck('cp_web_url')->first();
        event(new UserLoggedOut(access()->user()));
        auth()->logout();
        //Session::flush();
        if($isAdminLoogedIn != null && $adminData != null){
            session(['is_admin_logged_in' => $isAdminLoogedIn, 'adminData' => $adminData]);
        }
        $url = property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : 'login/';
        return redirect($url.'/'.$slug);
    }

    /**
     * This is here so we can use the default Laravel ThrottlesLogins trait
     *
     * @return string
     */
    public function username()
    {
        return 'uname';
    }

    /**
     * @param Request $request
     * @param $throttles
     * @return \Illuminate\Http\RedirectResponse
     * @throws GeneralException
     */
    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {   
        // dd($request->)
        // dd('kjhgf');
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }
        
        /**
         * Check to see if the users account is confirmed and active
         */
        /*$bus_id = access()->user()->business_id;
        $weburl = Business::select('cp_web_url')->find($bus_id);*/
        if (access()->user()->account_type == 'Client') {
            auth()->logout();
            throw new GeneralException(trans('auth.failed'));
        }
        /*else if($weburl->cp_web_url != $request->businessUrl){
            auth()->logout();
            throw new GeneralException(trans('auth.failed'));
        }*/
        elseif (! access()->user()->isConfirmed()) {
            $token = access()->user()->confirmation_code;
            $status = access()->user()->confirmed;
            auth()->logout();
            if($status == '2')
                $status = 'Under Review';
            if($status == '3')
                $status = 'On Hold';
            if($status == '0')
                $status = 'In Process';
            if($token != '')
                // throw new GeneralException('Your account is not confirmed. Please click the confirmation link in your e-mail, or <a href="'.route('test.resendConfirmEmail', $token).'">click here</a> to resend the confirmation e-mail.');
                throw new GeneralException('Your account is '.$status);
            else
                throw new GeneralException('Your account confirmation is in pending process.');

            //throw new GeneralException(trans('exceptions.frontend.auth.confirmation.resend', ['token' => $token]));
        } elseif (! access()->user()->isActive()) {
            auth()->logout();
            throw new GeneralException(trans('exceptions.frontend.auth.deactivated'));
        }

        
        event(new UserLoggedIn(access()->user()));

        /*if(!Session::has('businessId')){
            $business = Auth::user()->businesses;
            if($business)
                Session::put('businessId' , $business->id); 
                Session::put('hostname', 'crm'); 
        }
        if(!Session::has('ifBussHasStaffs') && Session::has('businessId')){
            $staffs = Business::find(Session::get('businessId'))->staffs;
            if(count($staffs))
                Session::put('ifBussHasStaffs', true);  
        }
        if(!Session::has('ifBussHasClients') && Session::has('businessId')){
            $clients = Business::find(Session::get('businessId'))->clients;
            if(count($clients))
                Session::put('ifBussHasClients', true);  
        }
        if(!Session::has('ifBussHasContacts') && Session::has('businessId')){
            $contacts = Business::find(Session::get('businessId'))->contacts;
            if(count($contacts))
                Session::put('ifBussHasContacts', true);    
        }*/
        // dd($this->redirectPath());
        return redirect()->intended($this->redirectPath());
    }

    public function checkUserType(Request $request){
        $slugData = Business::where('cp_web_url',$request->slug)->select('id')->get();
        //$slugData = Business::where('cp_web_url', 'oky')->select('id')->get();
        $data1 = 0;
        if(count($slugData)){
            $data1=$slugData[0]->id;
            $user = User::where('email', $request->userName)->where('business_id',$data1)->select('business_id', 'account_type')->get();
        }
        else{
            $user = User::where('email', $request->userName)->where('web_url', $request->slug)->select('business_id', 'account_type')->get();
            return array('usertype' =>'Admin', 'totalaccounts' => 1);
        }


		$data2 = array();
        foreach($user as $userdata){
         $data2[] = $userdata->account_type;
        }
        $countusers= count($data2);
       
      
        //DB::enableQueryLog();
       /* $user = User::whereHas('businesses', function ($query) use ($request){
                        $query->where('cp_web_url', $request->slug);
                    })
            ->where('email', $request->userName)->get();
       */  
    
       // $data1 = $user[0]->business_id;
        //$data2[] = $user[0]->account_type;
       /* foreach($user as $userdata){
         $data2[] = $userdata->account_type;
        }
        $countusers = count($data2);
        */
       //dd(DB::getQueryLog());
        //dd($user);
        //dd($data1);
        //dd($user);
        $a = array('businessid' =>$data1 ,'usertype' =>$data2 , 'totalaccounts' =>$countusers);
        if($data1)
            return $a;
        else
            return 0;

        /*$businessData = User::where('email', $request->userName)->select('business_id', 'account_type')->get();
        dd($businessData);
        if($businessData->count()){

        }
        foreach($businessData as $businessId){
          $storeid[]=$businessId->business_id;
          $storetype[]=$businessId->account_type;
        }
        $slugData = Business::whereIn('id',$storeid)->select('cp_web_url')->get();
        $getSlug=$slugData[0]->cp_web_url;
        if($getSlug == $request->slug){
           // dd($storetype);
           echo json_encode($storetype);
        }*/
        
        
    }
}
