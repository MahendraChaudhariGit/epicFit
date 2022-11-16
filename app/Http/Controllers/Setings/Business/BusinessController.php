<?php
namespace App\Http\Controllers\Setings\Business;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Business;
use App\Service;
use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use DB;
use App\UserType;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\SalesTools\Invoice\SalesToolsInvoiceTrait;
use App\Http\Traits\CalendarSettingTrait;
use App\Http\Traits\ChartSettingTrait;
use Illuminate\Support\Str;


//use App\SalesToolsInvoice;

class BusinessController extends Controller{
    use HelperTrait, SalesToolsInvoiceTrait, CalendarSettingTrait, ChartSettingTrait;

    public function create(){
        if(Session::has('businessId') && !Auth::user()->hasPermission(Auth::user(), 'create-business-basic'))
            abort(404);

        //if(Session::has('businessId'))
            //return Redirect::to(route('business.edit'));

        $permTypes = UserType::all();
        $permTyp = array('' => '-- Select --');
        foreach($permTypes as $permType)
            $permTyp[$permType->ut_id] = $permType->ut_name;
        
        $time_zone = ['' => '-- Select --'] + \TimeZone::getTimeZone();
        $country = ['' => '-- Select --'] + \Country::getCountryLists();
        $currency = ['' => '-- Select --'] + \Currency::$currencies;
        $businessTypes = ['' => '-- Select --'] + Business::getBusinessTypes(Auth::user()->id);
        $serviceTypes = ['' => '-- Select --'] + Service::getServiceTypes();
        $serviceCats = ['' => '-- Select --'] + Service::getServiceCats();
        $contactTypes = ['' => '-- Select --'] + Contact::getContactTypes();
        //dd($serviceTypes);
        $this->emptyFileuploadPluginUploadDir();
        $newres = [];

        $catData = $this->getProductCat();
        $pro_cat = $catData['pro_cat'];
        $parentCat=$catData['parentCat'];

        $proSize = $this->getProductSize();

        $pwd = genPwd();

        $web_url = Auth::user()->web_url;

        return view('Settings.business_setup2', compact('permTyp','country','currency','time_zone','businessTypes', 'serviceTypes', 'serviceCats', 'contactTypes','pro_cat', 'parentCat', 'pwd','proSize','web_url'));
    }

    public function uploadFile(Request $request){
        $business = Business::find($request->id);
        if($business /*&& Auth::user()->hasPermission(Auth::user(), 'edit-business-basic')*/){
            $business->update(array('logo' => $request->photoName));
            return url('/uploads/thumb_'.$request->photoName);
        }
        return '';
    }
    
    public function typeSave(Request $request){
        if(!Auth::user()->hasPermission(Auth::user(), 'create-business-type')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }

        $this->validate($request, ['value' => 'required']);
        $businesType = trim($request->value);
        $canInsert = false;
        $businessTypes = Business::getBusinessTypes(Auth::user()->id);
        if(!empty($businessTypes)){
            $businessTypesTemp = array_values($businessTypes);
            if(!in_array(strtolower($businesType), array_map('strtolower', $businessTypesTemp)))
                $canInsert = true;
        }
        else
            $canInsert = true;
        if($canInsert){
            $insId = DB::table('business_types')->insertGetId(
                ['bt_value' => $businesType, 'bt_user_id' => Auth::user()->id, 'bt_created_at' => 'now()', 'bt_updated_at' => 'now()']
            );
            return $insId;
        }
        else return '0';
    }

    public function store(Request $request){
        $isError = false;
        $msg = [];
        if(Session::has('businessId') && !Auth::user()->hasPermission(Auth::user(), 'create-business-basic')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        if(!$isError){
            /*if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'business'])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }*/

            /*print_r(Business::phoneNumbExist($request->phone));
            exit;
            if(Business::phoneNumbExist($request->phone)){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }*/
            /*if(Business::phoneNumbExist($request->phone)){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
                $isError = true;
            }*/

            /*if(!$this->ifUrlAvailable(['cp_web_url' => $request->cp_web_url, 'entity' => 'business'])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('urlExist' => 'This URL is already in use!');
                $isError = true;
            }*/
            $businessSlug = $this->getBusinessSlug($request->cp_web_url);
            if(!$businessSlug || $businessSlug == 'cp_not_available'){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('urlExist' => 'This url is already in use!');
                $isError = true;
            }

            /*$email = Business::where('email', '=', $request->email)->count();
            if($email > 0){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }*/

            if(!$isError){
                $insertData = array('trading_name' => $request->trading_name, 'type' => $request->type, 'description' => $request->description, 'cp_first_name' => $request->cp_first_name, 'cp_last_name' => $request->cp_last_name, 'cp_web_url' => $businessSlug/*$request->cp_web_url*/, 'relationship' => $request->relationship, 'currency' => $request->currency, 'time_zone' => $request->time_zone, 'logo' => $request->logo, 'website' => $request->website, 'facebook' => $request->facebook, 'email' => $request->email, 'phone' => $request->phone, 'address_line_one' => $request->address_line_one, 'address_line_two' => $request->address_line_two, 'city' => $request->city, 'country' => $request->country, 'state' => $request->state, 'postal_code' => $request->postal_code);

                if(isset($request->venue_location))
                    $insertData['venue_location'] = $request->venue_location;
                if(isset($request->billing_info))
                    $insertData['billing_info'] = $request->billing_info;

                $addedBusiness = Auth::user()->businesses()->create($insertData);
                Auth::user()->businesParent()->associate($addedBusiness)->save();
                // Auth::user()->update(['web_url'=>NULL]);

                /*$salestoolsinvoice = new SalesToolsInvoice;
                $salestoolsinvoice->sti_business_id = Session::get('businessId');
                $salestoolsinvoice->sti_payment_terms = 'Immediately';
                $salestoolsinvoice->sti_title = 'Invoice title';
                $salestoolsinvoice->sti_next_invoice_number = 1;
                if($salestoolsinvoice->save())
                    Session::put('ifBussHasSalesToolsInvoice', true);*/
                Session::put('businessId' , $addedBusiness->id);
                Session::put('timeZone', $request->time_zone);
                Session::put('hostname', 'crm');

                if($addedBusiness->user_id == Auth::user()->id)
                    Session::put('isSuperUser', true); 
                else
                    Session::put('isSuperUser', false);

                $this->setTimeZone();
                $this->createInvoice();
                $this->createCalendarSettings();
                $this->createChartSetting();
                
                $msg['status'] = 'added';
                $msg['insertId'] = $addedBusiness->id;
                $msg['businessSlug'] = $businessSlug;

                  
                //Session::put('timeZone', $request->time_zone);
               
                $timestamp = createTimestamp();
                $defaultServiceData = array(
                        array('business_id' => $addedBusiness->id, 'category' => 2, 'one_on_one_name' => 'Consultation', 'created_at' => $timestamp, 'updated_at' => $timestamp, 'is_default' => 1, 'for_sales_process_step' => 2),
                        array('business_id' => $addedBusiness->id, 'category' => 2, 'one_on_one_name' => 'Pre-Benchmarking', 'created_at' => $timestamp , 'updated_at' => $timestamp, 'is_default' => 1, 'for_sales_process_step' => 4)
                    );
                Service::insert($defaultServiceData);

                /*$service = new Service;
                $service->business_id = $addedBusiness->id;
                $service->category = 1;
                $service->team_name = 'Pre-Team Training';
                $service->is_default = 1;
                $service->for_sales_process_step = 6;
                $service->save();*/
                Session::put('ifBussHasServices' , true);
            }
        }
        return json_encode($msg);
    }

    public function editt(){
        $business = Business::findOrFail(Session::get('businessId'));
        
        if(!Auth::user()->hasPermission(Auth::user(), 'edit-business-basic'))
            abort(404);

        //if(!Session::has('businessId'))
            //return Redirect::to('settings/business/create');

        //$business = Business::findOrFail(Session::get('businessId'));

        //if($business){
        
            $time_zone = ['' => '-- Select --'] + \TimeZone::getTimeZone();

            $country = ['' => '-- Select --'] + \Country::getCountryLists();

            $currency = ['' => '-- Select --'] + \Currency::$currencies;

            $businessTypes = ['' => '-- Select --'] + Business::getBusinessTypes(Auth::user()->id);   
            return view('Settings.business.edit', compact('business', 'country', 'currency', 'time_zone', 'businessTypes'));
        //}
    }

    /*public function edit($id){
        if(!Auth::user()->hasPermission(Auth::user(), 'edit-business-basic'))
            abort(404);

        if(!Session::has('businessId'))
            return Redirect::to('settings/business/create');

        $business = Business::findOrFail($id);

        $savedData = array();
        if($business->venue_location){
            $savedData['address_line_one'] = $business->address_line_one;
            $savedData['address_line_two'] = $business->address_line_two;
            $savedData['city'] = $business->city;
            $savedData['country'] = $business->country;
            $savedData['state'] = $business->state;
            $savedData['postal_code'] = $business->postal_code;
            $savedData['time_zone'] = $business->time_zone;
        }

        $locations = $business->locations;
        $locs = array('' => '-- Select --');
        foreach($locations as $location)
            $locs[$location->id] = $location->location_training_area;

        $staffs = $business->staffs;
        $stff = array();
        foreach($staffs as $staff)
            $stff[$staff->id] = $staff->first_name.' '.$staff->last_name;

        $services = $business->services;
        $serv = array();
        foreach($services as $service){
            if($service->category == 1) // TEAM
                $serv[$service->id] = $service->team_name;
            else if($service->category == 2) // 1 on 1
                $serv[$service->id] = $service->one_on_one_name;
        }

        $classes = $business->classes;
        $clses = array();
        foreach($classes as $class)
            $clses[$class->cl_id] = $class->cl_name;

        $classCats = $business->classCats;
        $clsCat = array('' => '-- Select --');
        foreach($classCats as $classCat)
            $clsCat[$classCat->clcat_id] = $classCat->clcat_value;

        $permTypes = UserType::all();
        $permTyp = array('' => '-- Select --');
        foreach($permTypes as $permType)
            $permTyp[$permType->ut_id] = $permType->ut_name;
        
        $time_zone = ['' => '-- Select --'] + \TimeZone::getTimeZone();
        $country = ['' => '-- Select --'] + \Country::getCountryLists();
        $currency = ['' => '-- Select --'] + \Currency::$currencies;
        $businessTypes = ['' => '-- Select --'] + Business::getBusinessTypes(Auth::user()->id);
        $serviceTypes = ['' => '-- Select --'] + Service::getServiceTypes($id);
        $serviceCats = ['' => '-- Select --'] + Service::getServiceCats($id);
        $contactTypes = ['' => '-- Select --'] + Contact::getContactTypes($id);

        $temp = true;

        return view('Settings.business_setup2', compact('business', 'savedData', 'locs', 'stff', 'serv', 'clses', 'clsCat', 'permTyp', 'country','currency','time_zone','businessTypes', 'serviceTypes', 'serviceCats', 'contactTypes',  'temp'));
    }*/

    public function updatee($id, Request $request){
        $isError = false;
        $msg = [];

        if($id != Session::get('businessId') || !Auth::user()->hasPermission(Auth::user(), 'edit-business-basic')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            $business = Business::find($id);
            if($business){
                if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'business', 'id' => $id])){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                    $isError = true;
                }

                if(Business::phoneNumbExist($request->phone, $id)){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
                    $isError = true;
                }
                /*DB::enableQueryLog();
                print_r(Business::phoneNumbExist($request->phone, $id));
                //dd(DB::getQueryLog());
                exit;*/

                $businessSlug = $this->getBusinessSlug(end(explode('/',$request->cp_web_url)), $id);
                if(!$businessSlug){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('urlExist' => 'This url is already in use!');
                    $isError = true;
                }
                /*if(!$this->ifUrlAvailable(['cp_web_url' => $businessSlug, 'id' => $id])){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('urlExist' => 'This url is already in use!');
                    $isError = true;
                }*/
                
                if(!$isError){
                    $business->trading_name = $request->trading_name;
                    $business->type = $request->type;
                    $business->description = $request->description;
                    $business->cp_first_name = $request->cp_first_name;
                    $business->cp_last_name = $request->cp_last_name;
                    $business->relationship = $request->relationship;
                    $business->currency = $request->currency;
                    $business->time_zone = $request->time_zone;
                    $business->logo = $request->logo;
                    $business->website = $request->website;
                    $business->facebook = $request->facebook;
                    $business->email = $request->email;
                    $business->phone = $request->phone;
                    $business->address_line_one = $request->address_line_one;
                    $business->address_line_two = $request->address_line_two;
                    $business->city = $request->city;
                    $business->country = $request->country;
                    $business->state = $request->state;
                    $business->postal_code = $request->postal_code;
                    if($businessSlug != 'cp_not_available')
                        $business->cp_web_url = $businessSlug;
                    

                    //dd(Session::get('timeZone'));
                    /*$randomName = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
                    if(isset($request->cp_web_url) && $request->cp_web_url)
                        $business->cp_web_url = $request->cp_web_url;
                    else 
                       $business->cp_web_url = $randomName;*/

                    if(isset($request->venue_location) && $request->venue_location) 
                        $business->venue_location = $request->venue_location;
                    else
                        $business->venue_location = 0;

                    if(isset($request->billing_info) && $request->billing_info) 
                        $business->billing_info = $request->billing_info;
                    else
                        $business->billing_info = 0;

                    $business->save();
                    
                    Session::put('timeZone', $request->time_zone);
                    $this->setTimeZone();

                    //Session::put('timeZone', $request->time_zone);
                    //date_default_timezone_set('Europe/London');
                   // \Config::set('app.timezone', $request->time_zone);

                    //dd(\Config::get('app.timezone'));

                    $msg['status'] = 'updated';
                    $msg['businessSlug'] =$business->cp_web_url;
                }
            }
        }
        return json_encode($msg);
    }

    /*public function update($id, Request $request){
        $isError = false;
        $msg = [];

        if(!Auth::user()->hasPermission(Auth::user(), 'edit-business-basic')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'business', 'id' => $id])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }

            /*$email = Business::where('email', '=', $request->email)->where('id', '<>', $id)->count();
            if($email > 0){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }*

            if(!$isError){
                $updateData = array('trading_name' => $request->trading_name, 'type' => $request->type, 'description' => $request->description, 'cp_first_name' => $request->cp_first_name, 'cp_last_name' => $request->cp_last_name, 'relationship' => $request->relationship, 'currency' => $request->currency, 'time_zone' => $request->time_zone, 'logo' => $request->logo, 'website' => $request->website, 'facebook' => $request->facebook, 'email' => $request->email, 'phone' => $request->phone, 'address_line_one' => $request->address_line_one, 'address_line_two' => $request->address_line_two, 'city' => $request->city, 'country' => $request->country, 'state' => $request->state, 'postal_code' => $request->postal_code);

                if(isset($request->venue_location))
                    $updateData['venue_location'] = $request->venue_location;
                else
                    $updateData['venue_location'] = '';
                if(isset($request->billing_info))
                    $updateData['billing_info'] = $request->billing_info;
                else
                    $updateData['billing_info'] = '';

                $business = Business::findOrFail($id);
                $business->update($updateData);

                $msg['status'] = 'added';
                $msg['insertId'] = $id;
            }
        }
        return json_encode($msg);
    }*/

    public function destroy($id){
    }

    public function show($id){
    }

    public function index(){
    }

    protected function generateBusinessSlug(){
        //return substr(md5(uniqid(rand(), true)),0,5);
        return Str::random(5);
    }

    /*protected function getBusinessSlug($inputSlug, $businessId = 0){
        if($inputSlug){
            if($this->ifUrlAvailable(['cp_web_url' => $inputSlug, 'id' => $businessId]))
                return $inputSlug;
            else
                return false;
        }
        else{
            $businessSlug = $this->generateBusinessSlug();
            if($this->ifUrlAvailable(['cp_web_url' => $businessSlug, 'id' => $businessId]))
                return $businessSlug;
            else
                return $this->generateBusinessSlug($inputSlug, $businessId);
        }
    }*/

    protected function getBusinessSlug($inputSlug, $businessId = 0){
        if($inputSlug){
            if($this->ifUrlAvailable(['cp_web_url' => $inputSlug, 'id' => $businessId]))
                return $inputSlug;
            else
                return false;
        }
        else{
            $businessSlug = Auth::user()->web_url;
            if($businessSlug && $this->ifUrlAvailable(['cp_web_url' => $businessSlug, 'id' => $businessId]))
                return $businessSlug;
            else
                return 'cp_not_available';
        }
    }

    protected function ifUrlAvailable($data = array()){
        if(!array_key_exists('cp_web_url', $data) || !$data['cp_web_url'])
            return false;

        if(array_key_exists('id', $data) && $data['id'])
            $urlCount = Business::where('cp_web_url', $data['cp_web_url'])->where('id', '<>', $data['id'])->count();
        else
            $urlCount = Business::where('cp_web_url', $data['cp_web_url'])->count();

        if($urlCount > 0)
            return false;
    
        return true;
        /*if(!array_key_exists('cp_web_url', $data) || !$data['cp_web_url'] || !array_key_exists('entity', $data) || !$data['entity'])
            return true;

        switch($data['entity']){
            case "business":
                $model = 'App\Business';
                $UrlCol = 'cp_web_url';
                $idCol = 'id';
                break;
          }
          
        if(array_key_exists('id', $data) && $data['id'])
            $urlCount = Business::where($UrlCol, $data['cp_web_url'])->where($idCol, '<>', $data['id'])->count();
        else
            $urlCount = Business::where($UrlCol, $data['cp_web_url'])->count();
        if($urlCount > 0)
            return false;
    
        return true;*/
    }

    public function changeStepStatus(Request $request){
        $businessId = Session::get('businessId');
        try{
            $business = Business::find($businessId);
            $business->update([
                'is_class_step_complete' => $request->is_class_step_complete
            ]);
            $msg['status'] = 'ok';
            $msg['message'] = 'Data updated successfully';
        } catch( \Throwable $e){
            $msg['status'] = 'error';
            $msg['message'] = 'Something went wrong';
        }
        
        return json_encode($msg);
    }
}
