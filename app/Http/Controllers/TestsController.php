<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Traits\TestTrait;
use App\Models\Access\User\User;
use App\Http\Traits\HelperTrait;
use App\Event;
use Mail;
use Input;
use DB;
use Session;
use App\Clients;
use App\Product;
use App\Staff;

class TestsController extends Controller{
    use TestTrait, HelperTrait;

    private $cookieSlug = 'exercise';

    public function index(){
	 	return view('test.register');
    }

    protected function checkAndStoreUser($data){
        $msg = [];
        
        if(!$this->ifEmailAvailable(['email' => $data['email'], 'entity' => 'user'])){
            $msg['status'] = 'error';
            $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
            return json_encode($msg);
        }
        else
            return $this->storeUser(['name' => $data['name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'password' => $data['password'], 'type' => $data['type'], 'ut_id' => 1]);
    }
	
    public function store(Request $request){
        
        $data = json_decode($this->checkAndStoreUser(['name' => $request->name, 'last_name' => $request->last_name, 'email' => $request->email, 'password' => $request->password, 'type' => 'Admin']));

        if($data->status == 'error'){
            foreach($data->errorData as $errorData){
                foreach($errorData as $errorType => $message){
                    if($errorType == 'emailExist'){
                        return Redirect::to(route('test'))
                            ->withInput($request->input())
                            ->with('message', 'error|This primary email address already exist.');
                    }
                }
            }
        }
        else if($data->status == 'added')
            return Redirect::to(route('auth.login'))
                ->with('message', 'success|Your account was successfully created. We have sent you an e-mail to confirm your account.');
        

        
		/*//if($request->email != '' && User::where('email', '=', $request->email)->exists())
        if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'user']))
			return Redirect::to(route('test'))
				->withInput($request->input())
				->with('message', 'error|This primary email address already exist.');
        else{
        	$confirmationCode = $this->generateConfirmationCode();
		
			$insertData = array('name' => $request->name, 'last_name' => $request->last_name, 'email' => $request->email, 'password' => bcrypt($request->password), 'confirmation_code' => $confirmationCode, 'ut_id' => 1/*, 'confirmed' => 1*);
			User::create($insertData);

			$this->sendConfirmationMail($confirmationCode, ['email' => $request->email, 'fullname' => $request->name.' '.$request->last_name]);

			/*Mail::send('test.verification_email', ['confirmation_code' => $confirmationCode], function($message) use ($request) {
				$message->from('support@epictrainer.com', app_name());

	            $message->to($request->email, $request->name.' '.$request->last_name)
	                ->subject(app_name().': '.'Account Verification');
	        });*

			return Redirect::to(route('auth.login'))
				->with('message', 'success|Your account was successfully created. We have sent you an e-mail to confirm your account.');
		}*/
    }

    /**
     * Verify user email from user mail
     * @param confirmationCode
     * @return json message
    **/
    public function verify($confirmationCode){
    	$user = User::whereConfirmationCode($confirmationCode)->first();
    	if($user){
            $user->confirmation_code = '';
            $user->confirmed = 1;
            $user->save();

            //temporary code inject 
            $this->sendConformationMailToAdmin($user);
            
            return Redirect::to('login/'.$user->web_url)->with('message', 'success|Your account has been successfully confirmed.');
    		/*return Redirect::to(route('auth.login'))
				->with('message', 'success|Your account has been successfully confirmed.');*/
    	}
    	else
    		return Redirect::to(route('auth.login'))
				->with('message', 'error|Your confirmation code does not match.');
    }  

    public function resendConfirmationEmail($confirmationCode){
    	$user = User::whereConfirmationCode($confirmationCode)->first();
    	if($user){
    		$confirmationCode = $this->generateConfirmationCode();

            $user->confirmation_code = $confirmationCode;
            $user->save();

    		$this->sendConfirmationMail($confirmationCode, ['email' => $user->email, 'fullname' => $user->name.' '.$user->last_name]);

    		return Redirect::to(route('auth.login'))
					->with('message', 'success|We have resent you an e-mail to confirm your account.');
    	}
    	else
    		return Redirect::to(route('auth.login'))
				->with('message', 'error|That confirmation code does not exist.');
    }

    /**
     * all business with in active business top to bottom
     * @param void
     * @return all business
    **/
    public function businessIndex(){
        if(!isUserType(['Admin']))
            abort(404);
        $where = array(
                    'account_id' => 0,
                    'account_type' => 'Admin'
                );

        $allBusiness = array();
        $search = Input::get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        
        //dd($length);
        if($search){
             $allBusiness = User::where($where)->where('business_id','<>', Session::get('businessId'))
                                  ->where(function($query) use($search){
                                        $query->orWhere('name', 'like', "%$search%");
                                  })
                                  ->orderBy('confirmed')
                                  ->paginate($length);
        }
        else
            $allBusiness = User::where($where)->where('business_id','<>', Session::get('businessId'))->orderBy('confirmed')->paginate($length);

        return view('inactive_user_list',compact('allBusiness'));
    }

    /**
     * selected business activate
     * @param id
     * @return message
    **/
    public function businessActive($id){
        if(!isUserType(['Admin']))
            abort(404);

        $user = User::find($id);
        if($user){
            $user->confirmation_code = '';
            $user->confirmed = 1;
            $user->save();
            return 'added';
        }
        return 'error'; 
    }

    /**
     * selected business deactivate
     * @param id
     * @return message
    **/
    public function businessInactive($id){
        if(!isUserType(['Admin']))
            abort(404);

        $user = User::find($id);
        if($user){
            $user->confirmed = 0;
            $user->save();
            return 'added';
        }
        return 'error';  
    }

    /**
     * slected business soft delete
     * @param business id
     * @return back
    **/
    public function destroy($id){
        if(!isUserType(['Admin']))
            abort(404);

        $user = User::findOrFail($id);
        if($user->count() && Session::get('businessId') != $user->business_id){

            //Deleting linked client                        
            Clients::where('business_id', $user->business_id)->get()->each(function($client) {
                $client->delete();
            });
                                    

            //Deleting linked staff                         
            Staff::where('business_id', $user->business_id)->get()->each(function($staff) {
                $staff->delete();
            });
                                    

            // Deleting linked product                         
            Product::where('business_id', $user->business_id)->get()->each(function($product) {
                $product->delete();
            });

            $user->delete();

            return redirect()->back()->with('message', 'success|Business has been deleted successfully.');
        }

        return redirect()->back()->with('message', 'error|Business not found.');
    } 
}
