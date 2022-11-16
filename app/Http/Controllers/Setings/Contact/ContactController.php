<?php
namespace App\Http\Controllers\Setings\Contact;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Contact;
use App\Business;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;
use App\Http\Traits\HelperTrait;
use Input;

class ContactController extends Controller{
    use HelperTrait;

    private $cookieSlug = 'contact';

	public function allContacts(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-contact')){
            if($request->ajax())
                return [];
            else
                abort(404);
        }

        //if(Session::has('businessId'))
            //$contacts = Business::find(Session::get('businessId'))->contacts;
        $contacts = Contact::OfBusiness()->get();
        //else
            //return [];

		$index = 0;
		$cnt = array();
		foreach($contacts as $contact){
			$cnt[$index]['id'] = $contact->id;
			$cnt[$index]['name'] = $contact->contact_name.($contact->preferred_name=='Contact Name'?'--':'').'|'.$contact->company_name.($contact->preferred_name=='Company Name'?'--':'');
			$index++;
		}
		return json_encode($cnt);
	}
	
    public function index(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-contact'))
            abort(404);

        $allContacts = array();
        $search = $request->get('search');
        //if(Session::has('businessId')){
            $length = $this->getTableLengthFromCookie($this->cookieSlug);
            //$allContacts = Contact::where('business_id', Session::get('businessId'))->paginate($length);
            if($search)
                $allContacts = Contact::OfBusiness()->where(function($query) use ($search){$query->orWhere('contact_name', 'like', "%$search%")->orWhere('company_name', 'like', "%$search%")->orWhere('email', 'like', "%$search%");})->paginate($length);
            else
                $allContacts = Contact::OfBusiness()->paginate($length);
            //$allContacts = Business::find(Session::get('businessId'))->contacts;
        //}
             
        return view('Settings.contact.index', compact('allContacts'));           
    }

    public function store(Request $request){
        $isError = false;
        $msg = [];

        if($request->businessId != Session::get('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-contact')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            /*if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'contact'])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }*/
            if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'contact'])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }

            if($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'contact'])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
                $isError = true;
            }

            if(!$isError){
                $insertData = [
                    'business_id' => $request->businessId, 
                    'type' => $request->type,
                    'company_name' => $request->company_name,
                    'service_offered' => $request->service_offered,
                    'contact_name' => $request->contact_name,
                    'preferred_name' => $request->preferred_name,
                    'notes' => $request->notes,
                    'website' => $request->website,
                    'facebook' => $request->facebook,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address_line_one' => $request->address_line_one,
                    'address_line_two' => $request->address_line_two,
                    'city' => $request->city,
                    'country' => $request->country,
                    'state' => $request->state,
                    'postal_code' => $request->postal_code,
                ];

                if(isset($request->location) && $request->location != '')
                    $insertData['location'] = implode(',',$request->location);

                if($request->type == 2 && isset($request->is_epic_trainer)) //Personal Trainer
                    $insertData['is_epic_trainer'] = $request->is_epic_trainer;

                //$business = Business::find($request->businessId);
                //$addedCont = $business->contacts()->create($insertData);
                $addedCont = Contact::create($insertData);
                Session::put('ifBussHasContacts', true);  

                $msg['status'] = 'added';
                $msg['insertId'] = $addedCont->id;
            }
        }
        return json_encode($msg);
    }

    public function typeSave(Request $request){
        if(!Auth::user()->hasPermission(Auth::user(), 'create-contact-type')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }

        $this->validate($request, ['value' => 'required']);
        $contactType = trim($request->value);
        $canInsert = false;
        $contactTypes = Contact::getContactTypes($request->ownerId);
        if(!empty($contactTypes)){
            $contactTypesTemp = array_values($contactTypes);
            if(!in_array(strtolower($contactType), array_map('strtolower', $contactTypesTemp)))
                $canInsert = true;
        }
        else
            $canInsert = true;
        if($canInsert){
            $insId = DB::table('contact_types')->insertGetId(
                ['ct_value' => $contactType, 'ct_business_id' => $request->ownerId, 'created_at' => 'now()', 'updated_at' => 'now()']
            );
            return $insId;
        }
        else return '0';
    }

    public function show($id){
        $contact = Contact::findOrFailContact($id);

        if(!Auth::user()->hasPermission(Auth::user(), 'view-contact'))
            abort(404);

        $countries = \Country::getCountryLists();
        //$contact = Contact::findOrFail($id);
        
        $contact->stateName = \Country::getStateName($contact->country, $contact->state);

        return view('Settings.contact.show', compact('contact', 'countries'));
    }

    public function edit($id,Request $request){
        $contact = Contact::findOrFailContact($id);

        if(!Auth::user()->hasPermission(Auth::user(), 'edit-contact'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');

       // $contact = Contact::find($id);
        //if($contact){
            $business = Business::with('locations')->find(Session::get('businessId'));
            $businessId = $business->id;

            $locs = array();
            if($business->locations->count()){
                foreach($business->locations as $location)
                    $locs[$location->id] = ucfirst($location->location_training_area);
                asort($locs);
            }

            $country =  \Country::getCountryLists();
            
            $states = $this->getStates($contact->country);

            $contactTypes = Contact::getContactTypes($businessId);
            asort($contactTypes);
            if($request->has('subview')){
                $subview = true;
            }

            return view('Settings.contact.edit', compact('contact', 'businessId', 'locs', 'country', 'states', 'contactTypes','subview'));
        //}
    }

    public function update($id, Request $request){
        $isError = false;
        $msg = [];

        $contact = Contact::findContact($id, $request->businessId);

        if(!$contact || !Auth::user()->hasPermission(Auth::user(), 'edit-contact')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            //$contact = Contact::find($id);
            //if($contact){
                /*if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'contact', 'id' => $id])){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                    $isError = true;
                }*/
                if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'contact', 'id' => $id])){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                    $isError = true;
                }
                if($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'contact', 'id' => $id])){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
                    $isError = true;
                }

                if(!$isError){
                    $contact->type = $request->type;
                    $contact->company_name = $request->company_name;
                    $contact->service_offered = $request->service_offered;
                    $contact->contact_name = $request->contact_name;
                    $contact->preferred_name = $request->preferred_name;
                    $contact->notes = $request->notes;
                    $contact->website = $request->website;
                    $contact->facebook = $request->facebook;
                    $contact->email = $request->email;
                    $contact->phone = $request->phone;
                    $contact->address_line_one = $request->address_line_one;
                    $contact->address_line_two = $request->address_line_two;
                    $contact->city = $request->city;
                    $contact->country = $request->country;
                    $contact->state = $request->state;
                    $contact->postal_code = $request->postal_code;
                    

                    if(isset($request->location) && $request->location != '')
                        $contact->location = implode(',', $request->location);

                    if($request->type == 2 && isset($request->is_epic_trainer)) //Personal Trainer
                        $contact->is_epic_trainer = $request->is_epic_trainer;
                    else
                        $contact->is_epic_trainer = 0;

                    $contact->save();

                    $msg['status'] = 'updated';
                }
            //}
        }
        if($request->subview_refresh == 'reload'){
            $msg['status'] = 'reload';
        }
        return json_encode($msg);
    }

    public function destroy($id){
        $contact = Contact::findOrFailContact($id);

        if(!isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'delete-contact'))
            abort(404);
        
        //$contact = Contact::find($id);
        //if($contact){
            $contact->delete();

            if(!Contact::OfBusiness()->exists())
                Session::forget('ifBussHasContacts');

            return redirect()->back()->with('message', 'success|Contact has been deleted successfully.');
            //route('contacts')
        //}
    }

    public function create(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-contact'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');

        $business = Business::with('locations')->find(Session::get('businessId'));
        $businessId = $business->id;

        $locs = array();
        if($business->locations->count()){
            foreach($business->locations as $location)
                $locs[$location->id] = ucfirst($location->location_training_area);
            asort($locs);
        }

        $country = ['' => '-- Select --'] + \Country::getCountryLists();

        $contactTypes = ['' => '-- Select --'] + Contact::getContactTypes($businessId);
        asort($contactTypes);
        if($request->has('subview')){
            $loc=$request->input('location_id');
            $subview = true;
        }

        return view('Settings.contact.edit', compact('businessId', 'locs', 'country', 'contactTypes','subview','loc'));
    }
}
