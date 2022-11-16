<?php
namespace App\Http\Controllers\Setings\Membership;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Traits\StaffEventHistoryTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\ClientTrait;
use App\MemberShipCategory;
use App\MemberShipGroup;
use App\MemberShipTax;
use App\IncomeCategory;
use App\MemberShip;
use App\Business;
use App\MemberShipAddTax;
use App\ClientMember;
use Session;
use Carbon\Carbon;
use DB;

class MembershipController extends Controller{
    use HelperTrait, ClientTrait /* , StaffEventHistoryTrait */;

    //private $cookieSlug = 'product';

    public function index(){
        if(!Session::has('businessId'))
            abort(404);

       // $allStaffs = array();
       // $search = Input::get('search');

            $length = $this->getTableLengthFromCookie('membership');
            
           /* if($search)
                $allStaffs = Staff::OfBusiness()->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->paginate($length);
            else*/
               // DB::enableQueryLog();
                $allMemberShip = MemberShip::with('categorymember')->OfBusiness()->paginate($length);

            
        //}
           

        return view('Settings.membership.index', compact('allMemberShip'));

    }

    public function create(Request $request){
        /*if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-product'))
            abort(404);*/

         //DB::enableQueryLog();
        $business = Business::with('services','classes','staffs','membershipcategory','membershipgroup','MemberShipTax','incomecategory','membership')->find(Session::get('businessId'));
        $businessId = $business->id;
        //DB::enableQueryLog();
         //dd(DB::getQueryLog());

        $serv = array();
        if($business->services->count()){
            foreach($business->services as $service){
                if($service->category == 1) // TEAM
                    $serv[$service->id] = ucfirst($service->team_name);
                else if($service->category == 2) // 1 on 1
                    $serv[$service->id] = ucfirst($service->one_on_one_name);
            }
            asort($serv);
        }
       // $staffServices = Staff::getServices(['staff'=>$staff, 'business'=>$business]);

        $clses = array();
        if($business->classes->count()){
            foreach($business->classes as $class)
                $clses[$class->cl_id] = ucfirst($class->cl_name);
            asort($clses); 
        }

        $businessmember = array();
        if($business->membership->count()){
            foreach($business->membership as $bmember)
                $businessmember[$bmember->id] = ucfirst($bmember->me_membership_label);
            asort($businessmember);
        }     

        $memberStaff = array();
            if($business->staffs->count()){
                foreach($business->staffs as $mStaff)
                    $memberStaff[$mStaff->id] = ucwords($mStaff->first_name.' '.$mStaff->last_name);
                asort($memberStaff); 
            }

        $memberCate = array();
            if($business->membershipcategory->count()){
                foreach($business->membershipcategory as $mCate)
                    $memberCate[$mCate->id] = ucfirst($mCate->mc_category_value); 
                asort($memberCate);
            }

        $memberGroup = array();
            if($business->membershipgroup->count()){
                foreach($business->membershipgroup as $mGroup)
                    $memberGroup[$mGroup->id] = ucfirst($mGroup->mg_group); 
                asort($memberGroup);
            }

         $incomeCategory = array();
            if($business->incomecategory->count()){
                foreach($business->incomecategory as $ccategory)
                    $incomeCategory[$ccategory->id] = ucfirst($ccategory->category_name);
                asort( $incomeCategory);         
            }
                    
         $memberTax = array();
            if($business->MemberShipTax->count())
                foreach($business->MemberShipTax as $mAddon)
                    $memberTax[$mAddon->id] = array('tax_label'=>$mAddon->mtax_label, 'tax_rate'=>$mAddon->mtax_rate); 

         if($request->has('subview'))
            $subview = true;                              
        
        
        return view('Settings.membership.edit', compact('businessId','serv','clses','memberStaff','memberCate','memberGroup','subview','incomeCategory','memberTax','businessmember'));
    }

    public function store(Request $request){
         $isError = false;
         $msg = [];

        if($request->businessId != Session::get('businessId')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        
        if(!$isError){
            $insertData = array('me_business_id' => $request->businessId, 'me_membership_label' => $request->membership_label, 'me_validity_length' => $request->me_validity_length, 'me_validity_type' => $request->me_validity_type, 'me_class_limit' => $request->class_limit, 'me_class_limit_length' => $request->class_limit_length, 'me_class_limit_type' => $request->class_limit_type, 'me_auto_renewal' => $request->auto_renewal, 'me_auto_renewal_type' => $request->renewal_type, /*'me_installment_plan' => $request->installment_plan,*/ 'me_installment_amt' => $request->installment_amount,'me_unit_amt' => $request->unit_amount,'me_tax' => $request->taxable, 'me_prorate' => $request->me_prorate, 'me_signup_fee' => $request->signup_fee, 'me_change_signup_fee' => $request->member_change_signup, 'me_enrollment_limit' => 10,'me_public' =>$request->public_member, 'me_public_description' => $request->public_description, 'me_due_signup' => $request->due_at_signup, 'enrollment_start_date'=> $request->enrollment_start_date, 'enrollment_end_date'=> $request->enrollment_end_date, 'mem_begins_on_date'=> $request->mem_begins_on_date,'mem_begins_on'=> $request->mem_begins_on,'me_begin_date'=> $request->me_begin_date,'membership_totaltax'=> $request->membership_totaltax, 'addOn_member'=>$request->addOn_member,'me_visible'=> $request->me_visible,'me_show_on_kiosk'=> $request->me_show_on_kiosk );
            
            if($request->income_category != '')
                $insertData['me_income_category'] = $request->income_category;

            $addedMembership = MemberShip::create($insertData);


            if($request->member_category != '')
                $addedMembership->categorymember()->attach($request->member_category);   

            /*if($request->mem_services != '')
            $addedMembership->servicemember()->attach($request->mem_services);*/

            $services = $request->all();
            ksort($services);
            $mem_services = array();
            $mem_limit = array();
            foreach ($services as $key => $value) {
                if(strpos($key, 'mem_services') !== false)
                    $mem_services[] = $value;
                else if(strpos($key, 'mem_limit') !== false)
                    $mem_limit[] = $value;
                else if(strpos($key, 'mem_type') !== false)
                    $mem_type[] = $value;
            }
            
            if(count($mem_services) && count($mem_limit) && count($mem_type)){
                for ($i=0; $i < count($mem_services); $i++)
                   $memberService[$mem_services[$i]]= array('sme_service_limit'=>$mem_limit[$i], 'sme_service_limit_type'=>$mem_type[$i]);
               
                $addedMembership->servicemember()->attach($memberService);
            }

            if($request->mem_Classes != '')
            $addedMembership->classmember()->attach($request->mem_Classes); 

            if($request->member_added_group != '')
            $addedMembership->groupmember()->attach($request->member_added_group);  

            if($request->notify_staff != '')
            $addedMembership->staffmember()->attach($request->notify_staff);     

             $allTaxData=$this->calcAllTaxDatatime($addedMembership->id,$request->all());    

            $msg['status'] = 'added';
            $msg['insertId'] = $addedMembership->id;
        }
       return json_encode($msg);
    }
     

    public function edit($id){

        $membership = MemberShip::findOrFail($id);
        $business = Business::with('services','classes','staffs','membershipcategory','membershipgroup','MemberShipTax','incomecategory','membership')->find(Session::get('businessId'));
        $businessId = $business->id;
        //DB::enableQueryLog();
         
        $memberTaxArr = $membership->membertax;
       // dd(DB::getQueryLog());
       
        $memberShipAllTaxId=[];
        foreach ($memberTaxArr as $attendee) {
            $memberShipAllTaxId[]=$attendee->mat_tax_id;
        }
       
        $serv = array();
        if($business->services->count()){
            foreach($business->services as $service){
                if($service->category == 1) // TEAM
                    $serv[$service->id] = ucfirst($service->team_name);
                else if($service->category == 2) // 1 on 1
                    $serv[$service->id] = ucfirst($service->one_on_one_name);
            }
            asort($serv);
        }
        //$memberServices = $membership->servicemember->pluck('id')->toArray();
        /*$memberServices = $membership->servicemember;*/  
        //dd($membership->servicesmember);
        $clses = array();
        if($business->classes->count()){
            foreach($business->classes as $class)
                $clses[$class->cl_id] = ucfirst($class->cl_name);
            asort($clses); 
        }

        $memberClass = $membership->classmember->pluck('cl_id')->toArray();       

        $businessmember = array();
        if($business->membership->count()){
            foreach($business->membership as $bmember){
                if($bmember->id!=$id)
                $businessmember[$bmember->id] = ucfirst($bmember->me_membership_label);
            }
            asort($businessmember);
        }


         $memberStaff = array();
            if($business->staffs->count()){
                foreach($business->staffs as $mStaff)
                    $memberStaff[$mStaff->id] = ucwords($mStaff->first_name.' '.$mStaff->last_name);
                asort($memberStaff); 
            }
        $selectedmemberStaff = $membership->staffmember->pluck('id')->toArray();        

         $memberCate = array();
            if($business->membershipcategory->count()){
                foreach($business->membershipcategory as $mCate)
                    $memberCate[$mCate->id] = ucfirst($mCate->mc_category_value); 
                asort($memberCate);
            }

        $memberCategory = $membership->categorymember->pluck('id')->toArray();         

         $memberGroup = array();
            if($business->membershipgroup->count()){
                foreach($business->membershipgroup as $mGroup)
                    $memberGroup[$mGroup->id] = ucfirst($mGroup->mg_group);
                asort($memberGroup); 
            }
        $selectedmemberGroup = $membership->groupmember->pluck('id')->toArray();          

         $incomeCategory = array(''=>' -- Select -- ');
            if($business->incomecategory->count()){
                foreach($business->incomecategory as $ccategory)
                    $incomeCategory[$ccategory->id] = ucfirst($ccategory->category_name);
                asort($incomeCategory);
            }
        
        $memberTax = array();
            if($business->MemberShipTax->count())
                foreach($business->MemberShipTax as $mAddon)
                    $memberTax[$mAddon->id] = array('tax_label'=>$mAddon->mtax_label, 'tax_rate'=>$mAddon->mtax_rate); 
        $businessId = Session::get('businessId');
        return view('Settings.membership.edit', compact('membership', 'businessId','serv','clses','memberStaff','memberCate','memberGroup','incomeCategory','memberTax','businessmember',/*'memberServices',*/'memberClass','memberCategory','selectedmemberGroup','selectedmemberStaff','memberShipAllTaxId'));
     }

    public function update($id, Request $request){
      
        $isError = false;
        $msg = [];
        $membership = MemberShip::find($id);
        if(!$isError){
            $membership->me_business_id = $request->businessId; 
            $membership->me_membership_label = $request->membership_label; 
            $membership->me_validity_length = $request->me_validity_length; 
            $membership->me_validity_type = $request->me_validity_type;
            $membership->me_class_limit = $request->class_limit; 
            $membership->me_class_limit_length = $request->class_limit_length;
            $membership->me_class_limit_type = $request->class_limit_type; 
            $membership->me_auto_renewal = $request->auto_renewal; 
            $membership->me_auto_renewal_type = $request->renewal_type;
            //$membership->me_installment_plan = $request->installment_plan; 
            $membership->me_installment_amt = $request->installment_amount; 
            $membership->me_unit_amt = $request->unit_amount;
            $membership->me_tax = $request->taxable;  
            $membership->me_prorate = $request->me_prorate;
            $membership->me_signup_fee = $request->signup_fee;
            $membership->me_change_signup_fee = $request->member_change_signup;
            //$membership->me_enrollment_limit = 10;
            $membership->me_public = $request->public_member; 
            $membership->me_public_description = $request->public_description;
            $membership->me_due_signup = $request->due_at_signup; 
            $membership->enrollment_start_date= $request->enrollment_start_date;
            $membership->enrollment_end_date= $request->enrollment_end_date; 
            $membership->mem_begins_on_date= $request->mem_begins_on_date;
            $membership->mem_begins_on= $request->mem_begins_on;
            $membership->me_begin_date= $request->me_begin_date;
            $membership->membership_totaltax= $request->membership_totaltax;
            $membership->addOn_member= $request->addOn_member;
            $membership->me_visible= $request->me_visible;
            $membership->me_show_on_kiosk= $request->me_show_on_kiosk;

            if($request->income_category != '')
                $membership->me_income_category = $request->income_category;
           
            $membership->save();

            if($request->member_category == '')
                $memberCategory = [];
            else
                $memberCategory = $request->member_category;

            $membership->categorymember()->sync($memberCategory);   

            $services = $request->all();
            ksort($services);
            $mem_services = array();
            $mem_limit = array();
            $mem_type = array();
            foreach ($services as $key => $value) {
                if(strpos($key, 'mem_services') !== false)
                    $mem_services[] = $value;
                else if(strpos($key, 'mem_limit') !== false)
                    $mem_limit[] = $value;
                else if(strpos($key, 'mem_type') !== false)
                    $mem_type[] = $value;
            }
            if(count($mem_services) && count($mem_limit) && count($mem_type))
                for ($i=0; $i < count($mem_services); $i++)
                   $memberService[$mem_services[$i]]= array('sme_service_limit'=>$mem_limit[$i], 'sme_service_limit_type'=>$mem_type[$i]);    
            else
               $memberService = []; 
            $membership->servicemember()->sync($memberService);

            if($request->mem_Classes == '')
                $memberClass = [];
            else
                $memberClass = $request->mem_Classes;

            $existClasses = $membership->classmember->pluck('cl_id')->toArray();

            # If membership class exist then update it
            $removedClasses = array_diff($existClasses, $memberClass);
            if($removedClasses && count($removedClasses)) {
                $this->updateFutureMembershipClass($removedClasses, $membership->id); 
               
                # Delete class membership 
                $classMemResult = DB::table('class_membership')
                ->where('cm_member_id', $id)
                ->whereIn('cm_cl_id', $removedClasses)
                ->delete();
            } 

            # If membership class not exist add it
            $addedClasses = array_diff($memberClass, $existClasses);
            if($addedClasses && count($addedClasses))
                $membership->classmember()->attach($addedClasses); 

            if($request->member_added_group == '')
                $memberGroup = [];
            else
                $memberGroup = $request->member_added_group;

            $membership->groupmember()->sync($memberGroup);  

            if($request->notify_staff == '')
                $memberStaff = [];
            else
                $memberStaff = $request->notify_staff;
            $membership->staffmember()->sync($memberStaff); 

            $allTaxData=$this->calcAllTaxDatatime($membership->id,$request->all());
            $msg['status'] = 'updated';
        }
        return json_encode($msg);
    }

    protected function calcAllTaxDatatime($memberShipId,$input){
        $tax_option = $data = $insertData=[];
        foreach($input as $key => $value){
            if(strpos($key, 'member_tax_option') !== false && $value)
                $tax_option[(int) str_replace("member_tax_option", "", $key)] = $value;
        }
        if(isset($tax_option) ){
            ksort($tax_option);
            reset($tax_option);

        MemberShipAddTax::where('mat_member_id',$memberShipId)->delete();
            foreach($tax_option as $key => $value){
               $timestamp = createTimestamp();
                $insertData[] = array('mat_member_id' => $memberShipId, 'mat_tax_id' => $input['member_tax_option'.$key],'mat_tax_order' => $key+1, 'created_at' => $timestamp, 'updated_at' => $timestamp);
              
            }
          if($insertData) 
           MemberShipAddTax::insert($insertData); 
          

        }


        
    }

     public function destroy($id){
		 if(!isUserEligible(['Admin'], 'delete-membership'))
            abort(404);
			
        $membership = MemberShip::findOrFail($id);
		ClientMember::where('cm_membership_id', $id)->update(['cm_status'=>'Removed']);
		$membership->delete();
        
        return redirect()->back()->with('message', 'success|Membership has been deleted successfully.');
    }
    public function createCategory(Request $request){

     /*if(!Auth::user()->hasPermission(Auth::user(), 'create-business-type')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }*/
        
         $this->validate($request, ['value' => 'required']);
         $data = array();
         $data['mc_category_value'] = trim($request->value);
         $data['mc_businesses_id'] = Session::get('businessId');
        
         $addCategory=MemberShipCategory::create($data);
        
         if($addCategory)
            return $addCategory->id;
          else
             return '0';

    }
    public function createIncomeCategory(Request $request){
		 $this->validate($request, ['value' => 'required']);
         $data = array();
         $data['category_name'] = trim($request->value);
         $data['business_id'] = Session::get('businessId');
        
         if($addCategory=IncomeCategory::create($data))
               return $addCategory->id;
         else
               return 0;    
        
         /*if($addCategory)
            return $addCategory->id;
          else
             return '0';*/
        dd($addCategory->id);       

    }

    public function createMemberGroup(Request $request){

     /*if(!Auth::user()->hasPermission(Auth::user(), 'create-business-type')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }*/
        
         $this->validate($request, ['value' => 'required']);
         $data = array();
         $data['mg_group'] = trim($request->value);
         $data['mg_businesses_id'] = Session::get('businessId');
        
         $addGroup=MemberShipGroup::create($data);
        
         if($addGroup)
            return $addGroup->id;
          else
             return '0';

    }


    public function storemembertax(Request $request){
         $isError = false;
         $msg = [];

        if($request->businessId != Session::get('businessId')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            $insertData =array('mtax_business_id' => $request->businessId, 'mtax_label' => $request->tax_label, 'mtax_rate' => $request->tax_rate);
            $addedTaxLabel = MemberShipTax::create($insertData);
            $msg['status'] = 'added';
            $msg['insertId'] = $addedTaxLabel->id;
            $msg['mtax_label'] = $addedTaxLabel->mtax_label;
            $msg['mtax_rate'] = $addedTaxLabel->mtax_rate;
         }
         return json_encode($msg);

    }
}
