<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Movement;
use App\MovementStepSetup;
use Carbon\Carbon;
use Auth;
use App\Http\Traits\HelperTrait;
use Session;
class MovementController extends Controller {
	use HelperTrait;
	
	/* Start: Save movement */ 
		public function store(Request $request){
			$isError = false;
			$msg = [];

			$insertedData = [];
			$formData = $request->all();
			ksort($formData);
			$squat=$lunge=$bend=$pull=$push=$rotation=[];
			foreach($formData as $key => $value){
				if(strpos($key, 'squat') !== false){
					$squat[] = $value;
				}
				if(strpos($key, 'lunge') !== false){
					$lunge[] = $value;
				}
				if(strpos($key, 'bend') !== false){
					$bend[] = $value;
				}
				if(strpos($key, 'pull') !== false){
					$pull[] = $value;
				}
				if(strpos($key, 'push') !== false){
					$push[] = $value;
				}
				if(strpos($key, 'rotation') !== false){
					$rotation[] = $value;
				}
			}

			if(array_key_exists('sqFront',$formData))
				$insertedData['move_squat_front'] = $formData['sqFront'];
			if(array_key_exists('sqBack',$formData))
				$insertedData['move_squat_back'] = $formData['sqBack'];
			if(array_key_exists('luFront',$formData))
				$insertedData['move_lunge_front'] = $formData['luFront'];
			if(array_key_exists('luBack',$formData))
				$insertedData['move_lunge_back'] = $formData['luBack'];
			if(array_key_exists('beFront',$formData))
				$insertedData['move_bend_front'] = $formData['beFront'];
			if(array_key_exists('beBack',$formData))
				$insertedData['move_bend_back'] = $formData['beBack'];
			if(array_key_exists('puFront',$formData))
				$insertedData['move_pull_front'] = $formData['puFront'];
			if(array_key_exists('puBack',$formData))
				$insertedData['move_pull_back'] = $formData['puBack'];
			if(array_key_exists('pusFront',$formData))
				$insertedData['move_push_front'] = $formData['pusFront'];
			if(array_key_exists('pusBack',$formData))
				$insertedData['move_push_back'] = $formData['pusBack'];
			if(array_key_exists('roFront',$formData))
				$insertedData['move_rotation_front'] = $formData['roFront'];
			if(array_key_exists('roBack',$formData))
				$insertedData['move_rotation_back'] = $formData['roBack'];

			if(array_key_exists('notesSELowerMovement',$formData))
				$insertedData['move_SELower_notes'] = $formData['notesSELowerMovement'];
			if(array_key_exists('notesSEUpperMovement',$formData))
				$insertedData['move_SEUpper_notes'] = $formData['notesSEUpperMovement'];

			if(array_key_exists('notesLELowerMovement',$formData))
				$insertedData['move_LELower_notes'] = $formData['notesLELowerMovement'];
			if(array_key_exists('notesLEUpperMovement',$formData))
				$insertedData['move_LEUpper_notes'] = $formData['notesLEUpperMovement'];

			if(array_key_exists('notesBELowerMovement',$formData))
				$insertedData['move_BELower_notes'] = $formData['notesBELowerMovement'];
			if(array_key_exists('notesBEUpperMovement',$formData))
				$insertedData['move_BEUpper_notes'] = $formData['notesBEUpperMovement'];

			if(array_key_exists('notesPELowerMovement',$formData))
				$insertedData['move_PELower_notes'] = $formData['notesPELowerMovement'];
			if(array_key_exists('notesPEUpperMovement',$formData))
				$insertedData['move_PEUpper_notes'] = $formData['notesPEUpperMovement'];

			if(array_key_exists('notesUELowerMovement',$formData))
				$insertedData['move_UELower_notes'] = $formData['notesUELowerMovement'];
			if(array_key_exists('notesUEUpperMovement',$formData))
				$insertedData['move_UEUpper_notes'] = $formData['notesUEUpperMovement'];

			if(array_key_exists('notesRELowerMovement',$formData))
				$insertedData['move_RELower_notes'] = $formData['notesRELowerMovement'];
			if(array_key_exists('notesREUpperMovement',$formData))
				$insertedData['move_REUpper_notes'] = $formData['notesREUpperMovement'];

			$insertedData['move_business_id'] = Session::get('businessId');
			$insertedData['move_client_id'] = $formData['clientId'];
			//$insertedData['move_score'] = $formData['score'];
			$timestamp = Carbon::now();
			$insertedData['created_at'] = $timestamp;
			$insertedData['updated_at'] = $timestamp;

			if(count($squat)){
				$insertedData['move_squat'] = groupValsToSingleVal($squat);
				$insertedData['move_squat_score'] = $formData['SquatStepVal'];
			}
			if(count($lunge)){
				$insertedData['move_lunge'] = groupValsToSingleVal($lunge);
				$insertedData['move_lunge_score'] = $formData['LungeStepVal'];
			}
			if(count($bend)){
				$insertedData['move_bend'] = groupValsToSingleVal($bend);
				$insertedData['move_bend_score'] = $formData['BendStepVal'];
			}
			if(count($pull)){
				$insertedData['move_pull'] = groupValsToSingleVal($pull);
				$insertedData['move_pull_score'] = $formData['PullStepVal'];
			}
			if(count($push)){
				$insertedData['move_push'] = groupValsToSingleVal($push);
				$insertedData['move_push_score'] = $formData['PushStepVal'];
			}
			if(count($rotation)){
				$insertedData['move_rotation'] = groupValsToSingleVal($rotation);
				$insertedData['move_rotation_score'] = $formData['RotationStepVal'];
			}
			$insertedData['save_status'] = $formData['save_status'];
			$insertedData['data_from'] = $formData['data_from'];
			$insertedData['step_name'] = $formData['stepName'];
			Movement::insert($insertedData);

			if(!$isError){
				$msg['status'] = 'succsess';
			}
			return json_encode($msg);
		}
	/* End: Save movement */ 

	/* Start: Edit movement */
		public function edit($id){
			$isError = false;
			$data = [];
			$steps = [];
			$stepData = [];

			$movement = Movement::find($id);
			if($movement->count()){
				if($movement->move_squat != '' || $movement->move_squat_front != '' || $movement->move_squat_back != ''){
					$stepsNotes['notesSELowerMovement'] = $movement->move_SELower_notes;
					$stepsNotes['notesSEUpperMovement'] = $movement->move_SEUpper_notes;
					$scoreVal['Squat'] = $movement->move_squat_score;
					$stepData['Squat'] = explode(',',$movement->move_squat);
					$stepData['Squat']['front'] = $movement->move_squat_front;
					$stepData['Squat']['back']  = $movement->move_squat_back;
					$stepData['Squat']['front_side']  = 'video';
					$stepData['Squat']['back_side']  = 'side-video';
				}
				if($movement->move_lunge != ''  || $movement->move_lunge_front != '' || $movement->move_lunge_back != ''){
					$stepsNotes['notesLELowerMovement'] = $movement->move_LELower_notes;
					$stepsNotes['notesLEUpperMovement'] = $movement->move_LEUpper_notes;
					$scoreVal['Lunge'] = $movement->move_lunge_score;
					$stepData['Lunge'] = explode(',',$movement->move_lunge);
					$stepData['Lunge']['front']  = $movement->move_lunge_front;
					$stepData['Lunge']['back']  = $movement->move_lunge_back;
					$stepData['Lunge']['front_side']  = 'video1';
					$stepData['Lunge']['back_side']  = 'side-video1';
				}
				if($movement->move_bend != ''  || $movement->move_bend_front != '' || $movement->move_bend_back != ''){
					$stepsNotes['notesBELowerMovement'] = $movement->move_BELower_notes;
					$stepsNotes['notesBEUpperMovement'] = $movement->move_BEUpper_notes;
					$scoreVal['Bend'] = $movement->move_bend_score;
					$stepData['Bend'] = explode(',',$movement->move_bend);
					$stepData['Bend']['front'] = $movement->move_bend_front;
					$stepData['Bend']['back'] = $movement->move_bend_back;
					$stepData['Bend']['front_side']  = 'video2';
					$stepData['Bend']['back_side']  = 'side-video2';
				}
				if($movement->move_pull != ''  || $movement->move_pull_front != '' || $movement->move_pull_back != ''){
					$stepsNotes['notesPELowerMovement'] = $movement->move_PELower_notes;
					$stepsNotes['notesPEUpperMovement'] = $movement->move_PEUpper_notes;
					$scoreVal['Pull'] = $movement->move_pull_score;
					$stepData['Pull'] = explode(',',$movement->move_pull);
					$stepData['Pull']['front']  = $movement->move_pull_front;
					$stepData['Pull']['back']  = $movement->move_pull_back;
					$stepData['Pull']['front_side']  = 'video3';
					$stepData['Pull']['back_side']  = 'side-video3';
				}
				if($movement->move_push != ''  || $movement->move_push_front != '' || $movement->move_push_back != ''){
					$stepsNotes['notesUELowerMovement'] = $movement->move_UELower_notes;
					$stepsNotes['notesUEUpperMovement'] = $movement->move_UEUpper_notes;
					$scoreVal['Push'] = $movement->move_push_score;
					$stepData['Push'] = explode(',',$movement->move_push);
					$stepData['Push']['front']  = $movement->move_push_front;
					$stepData['Push']['back']= $movement->move_push_back;
					$stepData['Push']['front_side']  = 'video4';
					$stepData['Push']['back_side']  = 'side-video4';
				}
				if($movement->move_rotation != ''  || $movement->move_rotation_front != '' || $movement->move_rotation_back != ''){
					$stepsNotes['notesRELowerMovement'] = $movement->move_RELower_notes;
					$stepsNotes['notesREUpperMovement'] = $movement->move_REUpper_notes;
					$scoreVal['Rotation'] = $movement->move_rotation_score;
					$stepData['Rotation'] = explode(',',$movement->move_rotation);
					$stepData['Rotation']['front'] = $movement->move_rotation_front;
					$stepData['Rotation']['back']= $movement->move_rotation_back;
					$stepData['Rotation']['front_side']  = 'video5';
					$stepData['Rotation']['back_side']  = 'side-video5';
				}
				$data['notes'] = $stepsNotes;
				$data['stepsData'] = $stepData;
				$data['scoreVal'] = $scoreVal;
				$data['step_name'] = explode(',',$movement->step_name);
			
				//$data['score'] = $movement->move_score;
				//$data['movementId'] = $movement->id;
				//dd($data);
			}
			else{
				$isError = true;
			}

			if(!$isError){
				$data['status'] = 'success';
			}
			return json_encode($data);
		}
	/* End: Edit movement */

	/* Start: Update moment */
		public function update($id, Request $request)
		{
			$isError = false;
			$msg = [];
			$updatedData = [];
			
			$formData = $request->all();
			ksort($formData);
			$squat=$lunge=$bend=$pull=$push=$rotation=[];
			foreach($formData as $key => $value){
				if(strpos($key, 'squat') !== false)
					$squat[] = $value;
				if(strpos($key, 'lunge') !== false)
					$lunge[] = $value;
				if(strpos($key, 'bend') !== false)
					$bend[] = $value;
				if(strpos($key, 'pull') !== false)
					$pull[] = $value;
				if(strpos($key, 'push') !== false)
					$push[] = $value;
				if(strpos($key, 'rotation') !== false)
					$rotation[] = $value;
			}

			if(array_key_exists('sqFront',$formData))
				$updatedData['move_squat_front'] = $formData['sqFront'] != "null" ? $formData['sqFront'] : NULL;
			if(array_key_exists('sqBack',$formData))
				$updatedData['move_squat_back'] = $formData['sqBack'] != "null" ? $formData['sqBack'] : NULL;
			if(array_key_exists('luFront',$formData))
				$updatedData['move_lunge_front'] = $formData['luFront'] != "null" ? $formData['luFront'] : NULL;
			if(array_key_exists('luBack',$formData))
				$updatedData['move_lunge_back'] = $formData['luBack'] != "null" ? $formData['luBack'] : NULL;
			if(array_key_exists('beFront',$formData))
				$updatedData['move_bend_front'] = $formData['beFront'] != "null" ? $formData['beFront'] : NULL;
			if(array_key_exists('beBack',$formData))
				$updatedData['move_bend_back'] = $formData['beBack'] != "null" ? $formData['beBack'] : NULL;
			if(array_key_exists('puFront',$formData))
				$updatedData['move_pull_front'] = $formData['puFront'] != "null" ? $formData['puFront'] : NULL;
			if(array_key_exists('puBack',$formData))
				$updatedData['move_pull_back'] = $formData['puBack'] != "null" ? $formData['puBack'] : NULL;
			if(array_key_exists('pusFront',$formData))
				$updatedData['move_push_front'] = $formData['pusFront'] != "null" ? $formData['pusFront'] : NULL;
			if(array_key_exists('pusBack',$formData))
				$updatedData['move_push_back'] = $formData['pusBack'] != "null" ? $formData['pusBack'] : NULL;
			if(array_key_exists('roFront',$formData))
				$updatedData['move_rotation_front'] = $formData['roFront'] != "null" ? $formData['roFront'] : NULL;
			if(array_key_exists('roBack',$formData))
				$updatedData['move_rotation_back'] = $formData['roBack'] != "null" ? $formData['roBack'] : NULL;
		

			if(array_key_exists('notesSELowerMovement',$formData))
				$updatedData['move_SELower_notes'] = $formData['notesSELowerMovement'];
			else
				$updatedData['move_SELower_notes'] ='';

			if(array_key_exists('notesSEUpperMovement',$formData))
				$updatedData['move_SEUpper_notes'] = $formData['notesSEUpperMovement'];
			else
				$updatedData['move_SEUpper_notes'] = '';

			if(array_key_exists('notesLELowerMovement',$formData))
				$updatedData['move_LELower_notes'] = $formData['notesLELowerMovement'];
			else
				$updatedData['move_LELower_notes'] = '';

			if(array_key_exists('notesLEUpperMovement',$formData))
				$updatedData['move_LEUpper_notes'] = $formData['notesLEUpperMovement'];
			else
				$updatedData['move_LEUpper_notes'] = '';

			if(array_key_exists('notesBELowerMovement',$formData))
				$updatedData['move_BELower_notes'] = $formData['notesBELowerMovement'];
			else 
				$updatedData['move_BELower_notes'] = '';

			if(array_key_exists('notesBEUpperMovement',$formData))
				$updatedData['move_BEUpper_notes'] = $formData['notesBEUpperMovement'];
			else
				$updatedData['move_BEUpper_notes'] = '';

			if(array_key_exists('notesPELowerMovement',$formData))
				$updatedData['move_PELower_notes'] = $formData['notesPELowerMovement'];
			else 
				$updatedData['move_PELower_notes'] = '';

			if(array_key_exists('notesPEUpperMovement',$formData))
				$updatedData['move_PEUpper_notes'] = $formData['notesPEUpperMovement'];
			else
				$updatedData['move_PEUpper_notes'] = '';

			if(array_key_exists('notesUELowerMovement',$formData))
				$updatedData['move_UELower_notes'] = $formData['notesUELowerMovement'];
			else
				$updatedData['move_UELower_notes'] = '';

			if(array_key_exists('notesUEUpperMovement',$formData))
				$updatedData['move_UEUpper_notes'] = $formData['notesUEUpperMovement'];
			else
				$updatedData['move_UEUpper_notes'] = '';

			if(array_key_exists('notesRELowerMovement',$formData))
				$updatedData['move_RELower_notes'] = $formData['notesRELowerMovement'];
			else
				$updatedData['move_RELower_notes'] = '';

			if(array_key_exists('notesREUpperMovement',$formData))
				$updatedData['move_REUpper_notes'] = $formData['notesREUpperMovement'];
			else
				$updatedData['move_REUpper_notes'] = '';

			$updatedData['move_business_id'] = Session::get('businessId');
			$updatedData['move_client_id'] = $formData['clientId'];
			//$updatedData['move_score'] = $formData['score'];
			$timestamp = Carbon::now();
			//$updatedData['created_at'] = $timestamp;
			$updatedData['updated_at'] = $timestamp;

			if(count($squat)){
				$updatedData['move_squat'] = groupValsToSingleVal($squat);
				$updatedData['move_squat_score'] = $formData['SquatStepVal'];
			}
			if(count($lunge)){
				$updatedData['move_lunge'] = groupValsToSingleVal($lunge);
				$updatedData['move_lunge_score'] = $formData['LungeStepVal'];
			}
			if(count($bend)){
				$updatedData['move_bend'] = groupValsToSingleVal($bend);
				$updatedData['move_bend_score'] = $formData['BendStepVal'];
			}
			if(count($pull)){
				$updatedData['move_pull'] = groupValsToSingleVal($pull);
				$updatedData['move_pull_score'] = $formData['PullStepVal'];
			}
			if(count($push)){
				$updatedData['move_push'] = groupValsToSingleVal($push);
				$updatedData['move_push_score'] = $formData['PushStepVal'];
			}
			if(count($rotation)){
				$updatedData['move_rotation'] = groupValsToSingleVal($rotation);
				$updatedData['move_rotation_score'] = $formData['RotationStepVal'];
			}
			$updatedData['save_status'] = $formData['save_status'];
			$updatedData['step_name'] = $formData['stepName'];
			Movement::where('id',$id)->update($updatedData);
			

			if(!$isError){
				$msg['status'] = 'succsess';
			}
			return json_encode($msg);

		}
	/* End: Update moment */

	/* Start: Movement step store */
		public function updateMovementSteps(Request $request){
			$msg = [];
			$timestamp = Carbon::now();
			$data = $request->all();
			$movementSetepSetup = MovementStepSetup::where('mss_client_id',$data['client_id'])->first();

			if (count($movementSetepSetup)) {
   				MovementStepSetup::where('mss_client_id',$data['client_id'])
   					->update([
	    				'mss_business_id' => Session::get('businessId'),
	    			 	'mss_client_id' => $data['client_id'],
	    			 	'mss_step_name' => json_encode($data['steps']),
	    			 	'updated_at' => $timestamp
    				]);
			}
			else{
				MovementStepSetup::insert([
    				'mss_business_id' => Session::get('businessId'),
    			 	'mss_client_id' => $data['client_id'],
    			 	'mss_step_name' => json_encode($data['steps']),
    			 	'updated_at' => $timestamp,
    			 	'created_at' => $timestamp
    			]);
    		}	

			$msg['status']='updated';
			return json_encode($msg);
		}
	/* End: Movement step store */

	/* Start: Delete Movement */
		public function destroy($id, Request $request){
			$movement = $request->all(); 
			$root=route('clients.show',$movement['clientId']).'?tab=movements';
	        $movement = Movement::findOrFail($id);
	        $movement->delete();
	        //return redirect()->back()->with('message', 'success|Resource has been deleted successfully.');
	        return redirect($root);
	    }
	/* End: Delete Movement */
	public function saveRecordVideo(Request $request){
		if( $request->has('fileToUpload')){
		$file = $request->file('fileToUpload');
		$timestamp = md5(time().rand());
		$extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
		$name = $timestamp.'.'.$extension;
		$file->move(public_path().'/movement-videos/', $name);
		return $name;
		}else{

		$file =  $request->data;
		$folderPath = public_path().'/movement-videos/';

		$image_parts = explode(";base64,", $file);
		$image_type_aux = explode("video/", $image_parts[0]);
		$image_type ='webm';
		// dd($image_parts,$image_type_aux,$image_type);

		$image_base64 = base64_decode($image_parts[1]);
		// $fileName = uniqid() . '.png';

			$timestamp = md5(time().rand());
				$fileName = $timestamp.'.'.$image_type;

		$file = $folderPath . $fileName;
		file_put_contents($file, $image_base64);
		return $fileName;
		}
		
	}
}

