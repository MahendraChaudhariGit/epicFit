<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Business;
use App\StaffAttendence;
use Illuminate\Http\Request;
use Auth;


class AttendenceRoasterController extends Controller{

	public function store(Request $request){
		if( ( isUserType(['Admin']) || isUserType(['Staff']) ) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-attendence'))
            abort(404);

			if($request->case == 'attended'){
				$insertData = array('sa_status'=>'attended', 'edited_start_time'=>$request->starttime,'edited_end_time'=>$request->endtime,'sa_notes'=>'');
				StaffAttendence::where('id',$request->recordid)->update($insertData);
				$result = array('Status' => 'success','case'=>'attend');
			}
			if($request->case == 'unattended'){
				$insertData = array('sa_status'=>'unattended','edited_start_time'=>null,'edited_end_time'=>null ,'sa_notes'=>'');
				StaffAttendence::where('id',$request->recordid)->update($insertData);
				$result = array('Status' => 'success','case'=>'unattend');
			}
			if($request->case == 'edited'){
				$insertData = array('sa_status'=>'edited','edited_start_time'=>$request->editedstarttime,'edited_end_time'=>$request->editedendtime,'sa_notes'=>$request->notes);
				StaffAttendence::where('id',$request->recordid)->update($insertData);
				$result = array('Status' => 'success','case'=>'edit');
			}
		   	//$insertData = array('sa_staff_id' => $request->staffid, 'sa_start_time'=>$request->starttime, 'sa_end_time'=>$request->endtime, 'sa_date'=>$request->attendencedate ,'sa_status'=>'attended');
			return json_encode($result);
	   	
	}


	/**
	 * Save new custom staff attendence
	 * @param
	 * @return
	**/
	public function save(Request $request){
		if((isUserType(['Admin']) || isUserType(['Staff'])) && !Auth::user()->hasPermission(Auth::user(),'create-staff-attendence'))
			abort(404);
		$response['status'] = 'error';
        $staff_attendence = new StaffAttendence;
        $staff_attendence->sa_start_time = $request->start_time;
        $staff_attendence->sa_end_time = $request->end_time;
        $staff_attendence->sa_staff_id = $request->staff;
        $staff_attendence->sa_status = 'edited';
        $staff_attendence->edited_start_time = $request->start_time;
        $staff_attendence->edited_end_time = $request->end_time;
        $staff_attendence->sa_date = $request->date;
        if($request->has('notes') && $request->notes != "")
        	$staff_attendence->sa_notes = $request->notes;
        if($staff_attendence->save())
        	$response['status'] = 'added';

        return json_encode($response);
	}


	/**
	 * Edit staff attendence
	 * @param attendence id
	 * @return staff attendence data
	**/
	public function edit($id){
		if((isUserType(['Admin']) || isUserType(['Staff'])) && !Auth::user()->hasPermission(Auth::user(),'edit-staff-attendence'))
            abort(404);

        $response['status'] = 'error';

        $staff_attendence = StaffAttendence::findOrFail($id);
        if(count($staff_attendence)){
        	if($staff_attendence->edited_start_time != null && $staff_attendence->edited_end_time != null){
	        	$response['start_time'] = $staff_attendence->edited_start_time;
	        	$response['end_time'] = $staff_attendence->edited_end_time;
	        }
	        else{
	        	$response['start_time'] = $staff_attendence->sa_start_time;
	        	$response['end_time'] = $staff_attendence->sa_end_time;
	        } 
	        $response['notes'] = $staff_attendence->sa_notes;
	        $response['status'] = 'success';
        }

		return json_encode($response);
	}


	/**
	 * Delete staff attendence
	 * @param attendence id
	 * @return status message
	**/
	public function distroy($id){
		if( ( isUserType(['Admin']) || isUserType(['Staff']) ) && !Auth::user()->hasPermission(Auth::user(), 'delete-staff-attendence'))
            abort(404);

        $response['status'] = 'error';

        $staff_attendence = StaffAttendence::findOrFail($id);
        if($staff_attendence->delete())
        	$response['status'] = 'deleted';

		return json_encode($response);
	}

}