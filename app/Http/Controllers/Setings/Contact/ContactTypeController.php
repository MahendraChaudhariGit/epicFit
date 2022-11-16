<?php
namespace App\Http\Controllers\Setings\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\ContactType;

class ContactTypeController extends Controller{

    public function index(){
        $return = [];

        $types = ContactType::OfBusiness()->select('ct_id', 'ct_value')->get();
        if($types->count()){

            foreach($types as $type){
                $return[] = ['id'=>$type->ct_id, 'name'=>$type->ct_value];
            }
        }

        return json_encode($return);
    }

    public function destroy($id){
        $type = ContactType::find($id);
        if($type){
            $type->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $contactType=ContactType::find($request->entityId); 
            $contactType->ct_value=$request->text;
            if($contactType->save())
                return $contactType->ct_id;
        }
        else{
            $contactType=new ContactType; 
            $contactType->ct_business_id= Session::get('businessId');
            $contactType->ct_value=$request->text;
            if($contactType->save())
                return $contactType->ct_id;   
        }
        return 'error';
    }

}