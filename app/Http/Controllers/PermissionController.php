<?php
namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Auth;
use App\UserType;
use App\Permission;
use App\UserPerm;
use Input;
//use Auth;

class PermissionController extends Controller{
    public function index(){
    	//if(!Auth::user()->hasPermission(Auth::user(), 'edit-permission'))
    	if(!isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'list-permission-group'))
            abort(404);

		$permissionInfo = array();
		$assinedPermission = array();
		$unAssinedPermission = array();
		

        $allPermission = Permission::all();
         $allPermissionData=array();
         $allPermAction=array();
        foreach ($allPermission as $pValue) {
        	
        	if (in_array($pValue->permission_group, $allPermissionData)) {
        		$allPermissionData[$pValue['permission_group']][$pValue['perm_id']]=array('p_displayname' => $pValue->perm_display_name,'p_action_type' => $pValue->perm_action_type);
        		
            }else
            $allPermissionData[$pValue['permission_group']][$pValue['perm_id']] = array('p_displayname' => $pValue->perm_display_name,'p_action_type' => $pValue->perm_action_type);
     
        }
/*
foreach ($allPermission as $pAction) {
$allPermAction[$pAction['perm_id']]= $pAction->perm_action_type;
}
$totalActionType = array_count_values($allPermAction);*/
//dd($totalActionType);
		/*if(Input::get('show-super-user') == '$2y$10$Q66pSjMwG8Pr65uvJMjtW.Bsp.pAVAONWhKQo1XVOUE85gAK9HKIq')
			$userTypes = UserType::with('perms')->get();
		else
			$userTypes = UserType::with('perms')->where('ut_is_super_user', 0)->get();*/
		$userTypes = UserType::with('perms')->get();
		//dd($userTypes);
		/*foreach($allPermission as $pkey=>$pValue){
			$allUniquePermission[$pValue['perm_id']] = $pValue['perm_display_name'];
		}
		foreach($userTypes as $key=>$userTypesName){
			$permissionInfo[$userTypesName['ut_id']] = Permission::getPermissionDetails($userTypesName['ut_id']);
			if(count($permissionInfo[$userTypesName['ut_id']])>0){
				foreach($permissionInfo[$userTypesName['ut_id']] as $perValue){
					$assinedPermission[$userTypesName['ut_id']][$perValue->up_perm_id] = array('p_displayname' =>$perValue->perm_display_name,'p_name' => $perValue->perm_name);

				}
			}
			if(count($permissionInfo[$userTypesName['ut_id']])>0){
				foreach($allUniquePermission as $permkey => $permValue){
					if(array_key_exists($permkey,$assinedPermission[$userTypesName['ut_id']])){
					} else {
						$unAssinedPermission[$userTypesName['ut_id']][$permkey] = array('p_displayname' =>$permValue);
					}
				}
			} else {
				foreach($allUniquePermission as $permkey => $permValue){
					$unAssinedPermission[$userTypesName['ut_id']][$permkey] = array('p_displayname' =>$permValue);
				}
			}
		}*/
		//dd($allPermissionData);
		return view('permission',compact('userTypes','allPermissionData'));
    }
	
	public function store(){ 
		//dd($_POST['perm_id']);
		$response = array('status' => '','type' =>'','html' => '');

		if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-permission-group')){
			$timestamp = createTimestamp();
			/*$event = new UserPerm;
	        $event->up_ut_id = $_POST['user_type_id'];
	        $event->up_perm_id = $_POST['perm_id'];*/
	        $data=array();
	        $perm_id=array();
	        $perm_id=$_POST['perm_id'];
			   foreach ($perm_id as  $premValue) {
					$data[]=array('up_ut_id'=>$_POST['user_type_id'],'up_perm_id'=>$premValue,'created_at'=>$timestamp);
			   }
	       
			
			if(UserPerm::insertPerm($data)){
				$response['status'] =  "ok";
				$response['type'] = "addPerm";
				
											
	     	}
	     }

		return json_encode($response);    	
	}

	public function destroy(){
		$response = array('status' => '','msg' =>'','type' => '','html' => '');

		if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-permission-group')){
			$data=array();
			$timestamp = createTimestamp();
			//$userName = $_POST['user_name'];
			$data = $_POST['perm_id'];
			//if(UserPerm::deletePerm($_POST['user_type_id'],$_POST['perm_id'])){
				if(UserPerm::where('up_ut_id', '=', $_POST['user_type_id'])->whereIn('up_perm_id', $data)->delete()){
				$response['status'] = 'ok';
				$response['type'] = "delPerm";
			
			}
		}
		return json_encode($response);
	}
	public function addGroup(){
		$response = array('status' => '','msg' =>'');

		if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-permission-group')){
			$userType = new UserType;
			$groupDetails = UserType::groupExists($_POST['group_name']);

			if($groupDetails != ''){
				$response['status'] =  "nok";
				$response['msg'] = "Group Type already exists";
			} else {
				$userType->ut_name = $_POST['group_name'];
				if($userType->save()){
					$response['status'] =  "ok";
					$response['msg'] = displayAlert("success|Group Type saved successfully");
				}
			}
		}

		return json_encode($response);    	
	}
	public function deleteGroup(){
		$response = array('status' => '','msg' =>'');

		if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-permission-group')){
			$userType = UserType::find($_POST['type_id']);
			$userPerm = UserPerm::where('up_ut_id',$_POST['type_id'])->update(['deleted_at' => date("Y-m-d h:i:s")]);
			if($userType || $userPerm){
				$userType->update();
				$userType->delete();
				$response['status'] =  "ok";
				$response['msg'] = "Group Type delete successfully";
			 }
		}
		return json_encode($response);    	
	}
}