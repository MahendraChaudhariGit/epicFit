<?php

namespace App\Http\Controllers\Result;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use Session;
use App\User;
use App\Business;
use App\Clients;
use App\Models\Access\User\User as UserUser;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\{Challenge, ChallengeFriend};

class UserController extends Controller {

  /**
   * Login blade
   * @param Business id
   * @return view login
   */
  public function index(Request $request, $businessUrl = '') {
    if($request->has('id') && $request->id){
      $throttles = in_array(
        ThrottlesLogins::class, class_uses_recursive(get_class($this))
      );
       $user = User::where('account_id',$request->id)->first();
       if($user){
        Auth::loginUsingId($user->id);
        return $this->handleUserWasAuthenticated($request, $throttles);
       }
    }
    $epicfitStudioLoggedUser = DB::table('epicfitstudio_login')
    ->where('business_id', $request->businessId)
    ->where('client_id', $request->clientId)
    ->first();

    if($epicfitStudioLoggedUser) {
      $throttles = in_array(
        ThrottlesLogins::class, class_uses_recursive(get_class($this))
      );

      $userDetails = json_decode($epicfitStudioLoggedUser->user_data, true);
      Auth::loginUsingId($userDetails['id']);

      return $this->handleUserWasAuthenticated($request, $throttles);
    } else
    return view('Result.login', compact('businessUrl', $businessUrl));
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
    $throttles = in_array(
      ThrottlesLogins::class, class_uses_recursive(get_class($this))
    );

    if ($throttles && $this->hasTooManyLoginAttempts($request)) {
      return $this->sendLockoutResponse($request);
    }
    if($request->businessUrl == ''){
            $loginData = array('email' => $request->uname, 'password' => $request->password, 'account_type' => 'Client');
    }
    else{
    $businessId = Business::where('cp_web_url',$request->businessUrl)->pluck('id')->first();
    $loginData = array('email' => $request->uname, 'password' => $request->password, 'account_type' => 'Client', 'business_id' => $businessId);
    }

    if (Auth::attempt($loginData, $request->has('remember'))) {
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
      return redirect()->back()->withErrors([$this->loginUsername() => 'Your account is not confirmed. ']);
    } else {
      Session::put('userType', Auth::user()->account_type);
      $business = Business::find(Auth::user()->business_id);
      if ($business) {
        Session::put('businessId', $business->id);
        Session::put('hostname', 'result');

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
      //dd(Session()->all());
      $msg = [
        'user-email' => access()->user()->email,
        'date' => date('Y-m-d'),
        'time' => date("h:i:sa"),
        'previous-url' => url()->previous(),
        'redirected-url' => url('/').'/new-dashboard',
    ];
    Log::info($msg);
    $challenge_user = \Session::get('challenge_data');
    if(!empty($challenge_user) && (Auth::user()->account_id == $challenge_user['challenge_receiver_id']) && (Auth::user()->email == $challenge_user['email']) ){
         $challenge_status_message = $this->challenge_invitation($challenge_user);   
        return redirect('epic/train-gain/fitness-mapper')->with(['page_name' => 'my_challenge', 'message'=> $challenge_status_message]);    
      } else{
        return redirect()->intended('new-dashboard');
      }
    }
  }

  /* challenge task */
  public function challenge_invitation($challenge_user){
        $challenge_receiver_name = $challenge_user['name']; 
        $challenge_receiver_id = $challenge_user['challenge_receiver_id'];
        $challenge_receiver_email = $challenge_user['email'];
        $challenge_friend = ChallengeFriend::with('challenge')
                        ->where('client_id',$challenge_receiver_id)
                        ->where('challenge_id',$challenge_user['challenge_id'])
                        ->where('status','No')
                        ->first();
       \Session::forget('challenge_data');
      if($challenge_friend){
            $challenge_sender = Clients::select('id','firstname','lastname','email')
                    ->where('id',$challenge_friend['challenge']['client_id'])
                    ->first();
      
            if($challenge_user['status'] == 'accepted'){
                    $update =  $challenge_friend->update([
                      'status'=> 'Accepted'
                  ]);
                $text = $challenge_receiver_name.' accepted your challenge.';
                $toaster_message = 'Accepted';
              }
              if($challenge_user['status'] == 'rejected'){
                  $update =  $challenge_friend->update([
                    'status'=> 'Denied'
                  ]);
                  $text = $challenge_receiver_name.' busy at the moment so unable to participate in your challenge.';
                  $toaster_message = 'Rejected';
                }
                 $this->challenge_status_mail($challenge_sender, $text, $challenge_receiver_id,$challenge_receiver_email);
            
                return $toaster_message;
              } else {
                  $toaster_message = 'Performed';
                return $toaster_message;
              }          
        }

  public function challenge_status_mail($challenge_sender, $text, $challenge_receiver_id, $challenge_receiver_email){
    $username = $challenge_sender['firstname'];
    $to = $challenge_sender['email'];
    $subject  = 'Challenge Status';
    $message = view('Result.challenge.challenge_status', compact('username','text'))->render();
    $mail = new PHPMailer(true);
    try {
        //$mail->isSMTP(); // tell to use smtp
        $mail->CharSet = "utf-8"; // set charset to utf8
        $mail->Host = 'epictrainer.com';
        $mail->SMTPAuth = false;
        $mail->SMTPSecure = false;
        $mail->Port = 2525; // most likely something different for you. This is the mailtrap.io port i use for testing.
        $mail->Username = 'webmaster@epictrainer.com';
        $mail->Password = 'S[WlD3]Tf4*K';
        $mail->setFrom("noreply@epictrainer.com", "EPIC Trainer Team");
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->addAddress($to, $username);
        $mail->SMTPOptions= array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        );
    $result =  $mail->send();

    } catch (phpmailerException $e) {
        dd($e);
    } catch (Exception $e) {
        dd($e);
    }

    }


/* end challenge task */

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
    if(Session::has('businessId')){
      $slag = Business::where('id',Session::get('businessId'))->pluck('cp_web_url')->first();
      $url = 'login/'.$slag;
    }
    else
      $url = 'login';

    $epicfitStudioLoggedUser = DB::table('epicfitstudio_login')
    ->where('business_id', Auth::user()->business_id)
    ->where('client_id', Auth::user()->account_id)
    ->first();

    if($epicfitStudioLoggedUser) 
      $epicfitStudioLoggedUser = DB::table('epicfitstudio_login')->where('business_id', Auth::user()->business_id)->where('client_id', Auth::user()->account_id)->delete();

    Auth::logout();

    Session::flush();
    return redirect($url);
  }

  public function checkClient(Request $request){
    $email = $request->email;
    $clients = User::where('account_type','Client')->where('email',$email)->get();
    $count = 0;
    $data = [];
    if(count($clients)){
      foreach ($clients as $client) {
        if(Hash::check($request->password, $client->password)){
          $count = $count + 1;
          $business = Business::find($client->business_id);
          $data[] = [
            'businessId' => $client->business_id,
            'url' => $business->cp_web_url,
            'name' => $business->trading_name,
          ];
        }
      }
    }
    if($count > 1){
      $openModal = true;
    }else{
      $openModal = false;
    }
    $response = [
      'openModal' => $openModal,
      'businessData' => $data
    ];
    return response()->json($response);
  }


  /**
   * @return register 
   */
  /*public function register($businessId = ''){
    return view('register', compact('businessId', $businessId));
  }*/

}
