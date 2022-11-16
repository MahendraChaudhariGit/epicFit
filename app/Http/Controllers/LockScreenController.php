<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Access\User\User;
use Session;
use Auth;
use View;

class LockScreenController extends Controller{

    private $cookieSlug = 'lockscreen';
    private $attemptCount = 0;

    /**
     * lock user
     * @param void
     * @return lock screen with user information
    **/
    public function lockuser(){
        $response['status'] = 'error';
        $user = Auth::user();
        if($user->count()){
            Session::put('lockstatus','userLocked');
            /*if($user->profile_picture != '')
                $response['userimage'] = url('uploads/thumb_'.$user->profile_picture);
            else
                $response['userimage'] = url('assets/images/media-user.png');

            $response['username'] = ucwords($user->name.' '.$user->last_name);
            $response['useremail'] = $user->email;*/
            $response['status'] = 'success';
        }
        return json_encode($response);
	 	//return view('lock_screen', compact('userimage','username','useremail'))/*->render()*/;
        //return View::make('lock_screen', compact('userimage','username','useremail'))->render();
    }


    /**
     * unlock user
     * @param 
     * @return
    **/
    public function unlockuser(Request $request){
       $response['status'] = 'error';
       $password = $request->password;
       $username = $request->username;
       $businessId = Session::get('businessId');
       $type = Auth::user()->account_type;
    
       if($this->attemptCount < 5){
           if(Auth::attempt(['email' => $username, 'password' => $password,'business_id'=>$businessId,'account_type'=>$type])){
                Session::forget('lockstatus');
                $response['status'] = 'success';
           }
           else{
                $this->attemptCount++;
                $response['msg'] = 'Password does not match.';
           }
        }
        else{
            event(new UserLoggedOut(access()->user()));
            Auth::logout();
            $slug = Business::where('id',$businessId)->pluck('cp_web_url')->first();
            return redirect('login/'.$slug);
        }

        return json_encode($response);
    }
    
}
