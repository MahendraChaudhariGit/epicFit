<?php
namespace App\Http\Controllers\Setings\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Auth;
use App\Http\Traits\HelperTrait;
use App\Administrator;
use Session;
use Input;
use App\Business;
use App\Http\Traits\TestTrait;
use App\Models\Access\User\User;
use App\UserType;
use DB;

class AdminController extends Controller{
	use HelperTrait , TestTrait;
    private $cookieSlug = 'admin';

    public function index(Request $request){
    	if(!Session::has('businessId') || !isUserEligible(['Admin'], 'list-administrator'))
            abort(404);

   		 $alladmins = array();
        $search = $request->get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
     
      //DB::enableQueryLog();
        if($search)
        {
            $alladmins = User::where('business_id',Session::get('businessId'))
          ->where('account_type','Admin')
          ->where('id','!=',Auth::id())
          ->where(function($query) use ($search){$query->orWhere('name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%")->orWhere('email', 'like', "%$search%");})->paginate($length);
        //  dd(DB::getQueryLog());
        } 
        else
            $alladmins = User::where('business_id',Session::get('businessId'))->where('account_type','Admin')->where('id','!=',Auth::id())->paginate($length);

       return view('Settings.Admin.index',compact('alladmins'));
    }

    protected function callStoreUser($data){ 
        return $this->storeUser(['name' => $data['name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'password' => $data['password']/*str_random(10)*/, 'businessId' => Session::get('businessId'), 'type' => 'Admin', 'telephone' => $data['telephone'], 'address_line_one' => $data['address_line_one'], 'address_line_two' => $data['address_line_two'], 'city' => $data['city'], 'country' => $data['country'], 'state' => $data['state'], 'profile_pic' => $data['profile_pic'], 'postal_code' => $data['postal_code'], 'ut_id' => $data['ut_id'] ]);
    }

    public function create(){
    	if(!Session::has('businessId') || !isUserEligible(['Admin'], 'create-administrator'))
            abort(404);
        $business = Business::findOrFail(Session::get('businessId'));
      	$countries = ['' => '-- Select --'] + \Country::getCountryLists();
      	$business->state = \Country::getStateName($business->country, $business->state);

        $permTypes = UserType::all();
        $permTyp = array('' => '-- Select --');
        if($permTypes->count()){
          foreach($permTypes as $permType)
            $permTyp[$permType->ut_id] = ucfirst($permType->ut_name);
          asort($permTyp); 
      	}

        $pwd = genPwd();

  		return view('Settings.Admin.create',compact('business','countries','permTyp', 'pwd'));
    }

    public function store(Request $request){
    	//if(!Session::has('businessId') || !isUserEligible(['Admin'], 'create-administrator'))
            //abort(404);
      $isError = false;
      if(!Session::has('businessId') || !isUserEligible(['Admin'], 'create-administrator')){
          if($request->ajax())
             $isError = true;
          else
            abort(404);
      }

      if($request->ajax())
        $msg = [];
        $notIn = ['mailinator','yopmail'];
        if (in_array(explode('.',explode('@',$request->email)[1])[0],$notIn))
        {
            $msg['status'] = 'error';
            $msg['errorData'][] = array('emailExist' => 'Please use your genuine email ids.');
            $isError = true;
        //    return redirect()->back()->with('flash_danger','Mailinator and Yopmail email not excepted here.');
        }
        if(!$isError){
          if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'admin'])){
                  $msg['status'] = 'error';
                  $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                  $isError = true;
          }

          if($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'admin'])){
              $msg['status'] = 'error';
              $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
              $isError = true;
          }
          if(!$isError){
            //dd($request->all());
            
            $msg = $this->callStoreUser(['name' => $request->firstname, 'last_name' => $request->lastname, 'email' => $request->email, 'telephone' => $request->phone, 'address_line_one' => $request->address_line_one,'address_line_two' => $request->address_line_two, 'city' => $request->city, 'country' => $request->country, 'state' => $request->state, 'profile_pic' => $request->photoName, 'postal_code' => $request->postal_code, 'ut_id' => $request->admin_permissions, 'password' => $request->newPassword]);
           
             Session::put('ifBussHasAdministrators', true);
            return $msg;

              /*if($data->save()){
                  Session::put('ifBussHasAdministrators', true);
                  $result = array("status"=>"created","adminid"=>$data->id);
              }
              else
                  $result = array("status"=>"fail");
              echo json_encode($result);*/
          }
        }

        if($request->ajax())
          return json_encode($msg);
        else{
            if($isError)
                abort(404);
            else
                return redirect('admin');
        }
    }

   	public function edit($id){
   		if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-administrator'))
            abort(404);

   		$admin = User::where('business_id',Session::get('businessId'))->where('account_type','Admin')->find($id);
      
   		
      $countries = ['' => '-- Select --'] + \Country::getCountryLists();
      $states = $this->getStates($admin->country);

      $permTypes = UserType::all();
      $permTyp = array('' => '-- Select --');
      if($permTypes->count()){
        foreach($permTypes as $permType)
          $permTyp[$permType->ut_id] = ucfirst($permType->ut_name);
        asort($permTyp);
      }
      //dd($states);
    	return view('Settings.Admin.edit',compact('admin','countries','states','permTyp'));
    }

    public function update(Request $request, $id){
   		$isError = false;

   		if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-administrator')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

      if($request->ajax())
        $msg = [];
        $notIn = ['mailinator','yopmail'];
        if (in_array(explode('.',explode('@',$request->email)[1])[0],$notIn))
        {
            $msg['status'] = 'error';
            $msg['errorData'][] = array('emailExist' => 'Please use your genuine email ids.');
            $isError = true;
        //    return redirect()->back()->with('flash_danger','Mailinator and Yopmail email not excepted here.');
        }
   		if(!$isError){
          if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'admin', 'id' => $id])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
          }
          if($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'admin', 'id' => $id])){
              $msg['status'] = 'error';
              $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
              $isError = true;
          }
          if(!$isError){
            $data = User::where('business_id',Session::get('businessId'))->where('account_type','Admin')->find($id);
            $data->name = $request->firstname;
  	        $data->last_name = $request->lastname;
            $data->email = $request->email;
            $data->telephone = $request->phone;
  	        $data->address_line_one = $request->address_line_one;
            $data->address_line_two = $request->address_line_two;
  	        $data->city = $request->city;
            $data->country = $request->country;
            $data->state = $request->state;
            $data->postal_code = $request->postal_code;
            $data->profile_picture = $request->photoName;
            $data->ut_id = $request->admin_permissions;

            if($request->newPassword!="")
            {
                $data->password=bcrypt($request->newPassword);
                //dd(bcrypt($request->newPassword));
            }
            $data->save();

            /*if($data->update())
                $result = array("status"=>"updated");
            else
                $result = array("status"=>"fail");

            return json_encode($result);*/
            if($request->ajax()){
                    $msg['status'] = 'updated';
                    $msg['message'] = displayAlert('success|Data has been updated successfully.');
                }
                else
                    Session::flash('flash_message', 'Data has been updated successfully.');
          }
        }

        if($request->ajax())
            return json_encode($msg);
        else{
           if($isError)
               abort(404);
         else
            return redirect('admin');
        }
    }

    /*public function uploadFile(Request $request){
     	dd("upload part");
        $admin = Administrator::find($request->id);
        if($admin){
            $admin->update(array('admin_profile_picture' => $request->photoName));
            return url('/uploads/thumb_'.$request->photoName);
        }
        return '';
    }*/


    public function destroy($id){
    	if(!Session::has('businessId') || !isUserEligible(['Admin'], 'delete-administrator'))
            abort(404);
        $admin = User::where('business_id',Session::get('businessId'))->where('account_type','Admin')->findOrFail($id);
        $admin->delete();

        /*if(!User::OfBusiness()->exists())
                Session::forget('ifBussHasAdministrators');*/
        if(!Business::administrators(Session::get('businessId')))
            Session::forget('ifBussHasAdministrators');

            return redirect()->back()->with('message', 'success|Admin has been deleted successfully.');        
    }
}
?>