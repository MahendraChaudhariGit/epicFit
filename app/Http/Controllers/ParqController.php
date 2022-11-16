<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
// use Request;
use App\Http\Controllers\Controller;
use App\Parq;
use App\ParqVersion;
use App\Clients;
use App\Staff;
use Auth;
use Carbon\Carbon;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Benchmarks;
use Illuminate\Http\Request;
//use App\Http\Traits\ClientTrait;

class ParqController extends Controller {
	use HelperTrait, StaffEventsTrait/*, ClientTrait*/;
	
	public function waiverSave(Request $request){
		$waiverId = Auth::user()->id;
		
		$formData = $request->all();
		/*$clientId = (int)$formData['client_id'];
		$client = Clients::find($clientId);
		$parq = $client->parq;*/
		$parq = Parq::findParq((int) $formData['parqId']);
		if($parq && (isSuperUser() || (($parq->waiverTerms && hasPermission('edit-parq')) || (!$parq->waiverTerms && hasPermission('create-parq'))))){
			$updateData = array('trainerwaiverDate' => $formData['trainerwaiverDate'],'waiver_id' => $waiverId);
			if(isset($formData['waiverTerms']))
				$updateData['waiverTerms'] = (int) $formData['waiverTerms'];
			
			$parq->update($updateData);
			return 'true';
		}
		return 'false';
		/*if(isset($formData['waiverTerms']) && $client->account_status != 'active'){
			$clientOldStatus = $client->account_status;
			$client->update(array('account_status' => 'Pre-Benchmarking'));

			if($clientOldStatus != 'Pre-Benchmarking')
				$this->processSalesProcessOnStatusChange($client, ['clientOldStatus' => $clientOldStatus/*, 'clientOldSaleProcessStep' => $clientOldSaleProcessStep*, 'clientNewStatus' => 'Pre-Benchmarking']);
		}*/
	}
	public function parqSave(Request $request){
	    $isError = false;
		$msg = [];

		$formData = $request->formData;
		ksort($formData);
		$parqId = (int)$formData['parqId'];
		$parq = Parq::findParq($parqId);

		//if(!$parq || !Auth::user()->hasPermission(Auth::user(), 'edit-parq')){
		if(!$parq || (!isSuperUser() && (($parq->waiverTerms && !hasPermission('edit-parq')) || !hasPermission('create-parq')))){
            //if($request->ajax())
                $isError = true;
            /*else
                abort(404);*/
        }
		
		if(!$isError){
			if((int)$formData['stepNumb'] == 1){
		        if(!$this->ifEmailAvailableInSameBusiness(['email' => $formData['primEm'], 'entity' => 'client', 'id' => $parq->client_id])){
					$msg['status'] = 'error';
	            	$msg['errorData'][] = array('emailExist' => 'This email is already in use!');
	            	$isError = true;
	            }

	            if($this->ifPhoneExistInSameBusiness(['numb' => $formData['contactNo'], 'entity' => 'client', 'id' => $parq->client_id])){
	                $msg['status'] = 'error';
	                $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
	                $isError = true;
	            }

	            if($formData['referrer']!="onlinesocial" && $formData['referrer']!="mediapromotions" ){
	            	$formData['referencewhere'] = '';
	            }
	            if($formData['referrer']!="socialmedia"){
	            	$formData['otherName'] = '';
	            }

		        if(!$isError){
		        	if($formData['heightUnit'] == 'Metric'){
		        		$height = $formData['height_metric'];
		        	}else{
		        		$height = $formData['height_imperial_ft'].'-'.$formData['height_imperial_inch'];
		        	}
		        	if($formData['weightUnit'] == 'Metric'){
		        		$weight = $formData['weight_metric'];
		        	}else{
		        		$weight = $formData['weight_imperial'];
					}
					if($formData['referralName']){
						$ref_Name = $formData['referralName'];

					}
					$updateData = array('hearUs' => $formData['referrer'], 'ref_name' => $ref_Name, 'referencewhere'=>$formData['referencewhere'],'referrerother'=>$formData['otherName'], 'firstName' => $formData['firstName'], 'lastName' => $formData['lastName'], 'heightUnit' => $formData['heightUnit'], 'height' => $height, 'weightUnit' => $formData['weightUnit'], 'weight' => $weight, 'dob' => prepareDob($formData['yyyy'], $formData['mm'], $formData['dd']), 'occupation' => $formData['occupation'], 'contactNo' => $formData['contactNo'], 'email' => $formData['primEm'], 'ecName' => $formData['ecName'], 'ecRelation' => $formData['ecRelation'], 'ecNumber' => $formData['ecNumber'], 'notes' => $formData['notes'], 'parq1' => 'completed');

					//$updateData['age'] = $parq->calcAge($updateData['dob']);
					$updateData['age'] = $this->calcAge($updateData['dob']);

					if(isset($formData['referralNetwork']))
						$updateData['referralNetwork'] = $formData['referralNetwork'];
					else{
						$updateData['referralNetwork'] = '';
					}
						
					if(isset($formData['gender']))
						$updateData['gender'] = $formData['gender'];
						
					if(isset($formData['addressline1']) && $formData['addressline1'] != ''){
						$updateData['addressline1'] = $formData['addressline1'];
						$updateData['addressline2'] = $formData['addressline2'];
						$updateData['city'] = $formData['city'];
						$updateData['country'] = $formData['country'];
						$updateData['addrState'] = $formData['addrState'];
						$updateData['postal_code'] = $formData['postal_code'];
						$updateData['timezone'] = $formData['timezone'];
						$updateData['currency'] = $formData['currency'];
						
					}
					else
						$updateData['addressline1'] = $updateData['addressline2'] = $updateData['city'] = $updateData['country'] = $updateData['addrState'] = $updateData['postal_code'] = $updateData['timezone'] = $updateData['currency'] = '';

					/* Create new version of Parq */
					if($parq->state == 'completed'){
						$oldParq = $parq;
						$newPrqVer = $oldParq->toArray();
						$this->saveParqVersion($newPrqVer);
					}

					Parq::where('id', $parqId)->update($updateData);
					
					$dataForClients = array('firstname' => $formData['firstName'], 'lastname' => $formData['lastName'], 'birthday' => $updateData['dob'], 'occupation' => $formData['occupation'], 'phonenumber' => $formData['contactNo'], 'email' => $formData['primEm']);
					
					if(isset($formData['gender']))
						$dataForClients['gender'] = $formData['gender'];
						
					if(isset($formData['addressline1']) && $formData['addressline1'] != '')
						$dataForClients['country'] = $formData['country'];
					else
						$dataForClients['country'] = '';
					
					$dataForClients['risk_factor']=Clients::calculateRiskFactor($parq);
						
					$parq->client()->update($dataForClients);

					if($updateData['dob'] != '' || $updateData['dob'] != '0000-00-00'){
						$currentYear = Carbon::now()->year;
						$date = $currentYear.'-'.$formData['mm'].'-'.$formData['dd'];
						$taskName = ucwords($formData['firstName'].' '.$formData['lastName']).' Birthday';
						$this->setTaskReminder($date, ['taskName'=>$taskName,'taskDueTime'=>'09:00:00','taskNote'=>'','remindBeforHour'=>1,'clientId'=>$parq->client->id]);	
					}		
				}
			}
			else if((int)$formData['stepNumb'] == 2){
				$updateData = array('activity' => $formData['activity'], 'activityOther' => $formData['activityOther'], 'frequency' => $formData['frequency'], 'paEnjoy' => $formData['paEnjoy'], 'paEnjoyNo' => $formData['paEnjoyNo'],'preferredTraingDays' => $formData['preferredTraingDays'], 'epNotes' => $formData['epNotes'], 'parq2' => 'completed');

				if($formData['paPerWeek'] != '')
                    $updateData['paPerWeek'] = groupValsToSingleVal($formData['paPerWeek']);

                  else
                  	$updateData['paPerWeek'] = '';

                if($formData['paSession'] != '')
                    $updateData['paSession'] = groupValsToSingleVal($formData['paSession']);

                  else
                  	$updateData['paSession'] = '';


				
				if($formData['intensity'] != '')
					$updateData['intensity'] = groupValsToSingleVal($formData['intensity']);
				else
					$updateData['intensity'] = '';
				
				if($formData['paIntensity'] != '')
					$updateData['paIntensity'] = groupValsToSingleVal($formData['paIntensity']);
				else
					$updateData['paIntensity'] = '';
				
				Parq::where('id', $parqId)->update($updateData);

				$parq->client()->update(['risk_factor'=>Clients::calculateRiskFactor($parq)]);
			}
			else if((int)$formData['stepNumb'] == 3){
				$headInjury = $neckInjury = $shoulderInjury = $armInjury = $handInjury = $backInjury = $hipInjury = $legInjury = $footInjury = [];
				foreach($formData as $key => $value){
					if(strpos($key, 'headInjury') !== false)
						$headInjury[] = $value;

					else if(strpos($key, 'neckInjury') !== false)
						$neckInjury[] = $value;

					else if(strpos($key, 'shoulderInjury') !== false)
						$shoulderInjury[] = $value;

					else if(strpos($key, 'armInjury') !== false)
						$armInjury[] = $value;

					else if(strpos($key, 'handInjury') !== false)
						$handInjury[] = $value;
					
					else if(strpos($key, 'backInjury') !== false)
						$backInjury[] = $value;

					else if(strpos($key, 'hipInjury') !== false)
						$hipInjury[] = $value;

					else if(strpos($key, 'legInjury') !== false)
						$legInjury[] = $value;

					else if(strpos($key, 'footInjury') !== false)
						$footInjury[] = $value;
				}

				if(array_key_exists("noInjury", $formData)){
					$updateData = array('headInjury' => '', 'neckInjury' => '', 'shoulderInjury' => '', 'armInjury' => '', 'handInjury' => '', 'backInjury' => '', 'hipInjury' => '', 'legInjury' => '', 'footInjury' => '', 'headInjuryNotes' => '', 'neckInjuryNotes' => '', 'backInjuryNotes' => '', 'footInjuryNotes' => '', 'legInjuryNotes' => '', 'hipInjuryNotes' => '', 'shoulderInjuryNotes' => '', 'armInjuryNotes' => '', 'handInjuryNotes' => '');
				} 
				else{
					$updateData = array('headInjury' => groupValsToSingleVal($headInjury), 'neckInjury' => groupValsToSingleVal($neckInjury), 'shoulderInjury' => groupValsToSingleVal($shoulderInjury), 'armInjury' => groupValsToSingleVal($armInjury), 'handInjury' => groupValsToSingleVal($handInjury), 'backInjury' => groupValsToSingleVal($backInjury), 'hipInjury' => groupValsToSingleVal($hipInjury), 'legInjury' => groupValsToSingleVal($legInjury), 'footInjury' => groupValsToSingleVal($footInjury), 'headInjuryNotes' => $formData['notesHeadInjury'], 'neckInjuryNotes' => $formData['notesNeckInjury'], 'backInjuryNotes' => $formData['notesBackInjury'], 'footInjuryNotes' => $formData['notesFootInjury'], 'legInjuryNotes' => $formData['notesLegInjury'], 'hipInjuryNotes' => $formData['notesHipInjury'], 'shoulderInjuryNotes' => $formData['notesShoulderInjury'], 'armInjuryNotes' => $formData['notesArmInjury'], 'handInjuryNotes' => $formData['notesHandInjury']);
				}

				$updateData['ipfhAdditionalNotes'] = $formData['ipfhAdditionalNotes'];
				$updateData['allergies'] = $formData['allergies'];
				$updateData['chronicMedication'] = $formData['chronicMedication'];
				$updateData['medicaNotes'] = $formData['medCondNotes'];
				$updateData['relMedicaNotes'] = $formData['relMedCondNotes'];
				$updateData['smoking'] = $formData['smoking'];
				$updateData['ipfhNotes'] = $formData['ipfhNotes'];
				$updateData['parq3'] = 'completed';
				
				if($formData['allergies'] == 'Yes')
					$updateData['allergiesList'] = $formData['allergiesList'];
				else
					$updateData['allergiesList'] = '';
					
				if($formData['chronicMedication'] == 'Yes')
					$updateData['chronicMedicationList'] = $formData['chronicMedicationList'];
				else
					$updateData['chronicMedicationList'] = '';
									
				if($formData['smoking'] == 'Yes' && isset($formData['smokingPerDay']))
					$updateData['smokingPerDay'] = $formData['smokingPerDay'];
				else
					$updateData['smokingPerDay'] = '';	
				
				if($formData['medicalCondition'] != '')
					$updateData['medicalCondition'] = groupValsToSingleVal($formData['medicalCondition']);
				else
					$updateData['medicalCondition'] = '';
				
				if($formData['relMedicalCondition'] != '')
					$updateData['relMedicalCondition'] = groupValsToSingleVal($formData['relMedicalCondition']);
				else
					$updateData['relMedicalCondition'] = '';
				
				if(isset($formData['noInjury']))
					$updateData['noInjury'] = $formData['noInjury'];
				else
					$updateData['noInjury'] = '';
					
				Parq::where('id', $parqId)->update($updateData);

				$parq->client()->update(['risk_factor'=>Clients::calculateRiskFactor($parq)]);
			}
			else if((int)$formData['stepNumb'] == 4){
				$ans = [];
				foreach($formData as $key => $value){
					if(strpos($key, 'ans') !== false)
						$ans[] = $value;
				}
				Parq::where('id', $parqId)->update(array('questionnaire' => groupValsToSingleVal($ans), 'parqNotes' => $formData['parqNotes'], 'parq4' => 'completed'));

				
			}
			else if((int)$formData['stepNumb'] == 5){
				$components = $headImprove = $neckImprove = $footImprove = $legImprove = $handImprove = $chestImprove = $coreImprove = $backImprove = $hipImprove = $shouldersImprove = $armsImprove = [];
				foreach($formData as $key => $value){
					if(strpos($key, 'goalFitnessComponents') !== false)
						$components[] = $value;
						
					else if(strpos($key, 'headImprove') !== false)
						$headImprove[] = $value;
						
					else if(strpos($key, 'neckImprove') !== false)
						$neckImprove[] = $value;
						
					else if(strpos($key, 'footImprove') !== false)
						$footImprove[] = $value;
					
					else if(strpos($key, 'legImprove') !== false)
						$legImprove[] = $value;
						
					else if(strpos($key, 'handImprove') !== false)
						$handImprove[] = $value;

					else if(strpos($key, 'chestImprove') !== false)
						$chestImprove[] = $value;

					else if(strpos($key, 'coreImprove') !== false)
						$coreImprove[] = $value;

					else if(strpos($key, 'backImprove') !== false)
						$backImprove[] = $value;

					else if(strpos($key, 'hipImprove') !== false)
						$hipImprove[] = $value;

					else if(strpos($key, 'shouldersImprove') !== false)
						$shouldersImprove[] = $value;

					else if(strpos($key, 'armsImprove') !== false)
						$armsImprove[] = $value;
				}
				
				$updateData = array('goalFitnessComponents' => $parq->pipeSepVal($components), 'headImprove' => groupValsToSingleVal($headImprove), 'neckImprove' => groupValsToSingleVal($neckImprove), 'footImprove' => groupValsToSingleVal($footImprove), 'legImprove' => groupValsToSingleVal($legImprove), 'handImprove' => groupValsToSingleVal($handImprove), 'chestImprove' => groupValsToSingleVal($chestImprove), 'coreImprove' => groupValsToSingleVal($coreImprove), 'backImprove' => groupValsToSingleVal($backImprove), 'hipImprove' => groupValsToSingleVal($hipImprove), 'shouldersImprove' => groupValsToSingleVal($shouldersImprove), 'armsImprove' => groupValsToSingleVal($armsImprove), 'headImproveNotes' => $formData['notesHeadImprove'], 'neckImproveNotes' => $formData['notesNeckImprove'], 'backImproveNotes' => $formData['notesBackImprove'], 'footImproveNotes' => $formData['notesFootImprove'], 'legImproveNotes' => $formData['notesLegImprove'], 'hipImproveNotes' => $formData['notesHipImprove'], 'shouldersImproveNotes' => $formData['notesShouldersImprove'], 'armsImproveNotes' => $formData['notesArmsImprove'], 'handImproveNotes' => $formData['notesHandImprove'], 'chestImproveNotes' => $formData['notesChestImprove'], 'coreImproveNotes' => $formData['notesCoreImprove'], 'achieveGoal' => $formData['achieveGoal'], 'supportFamily' => $formData['supportFamily'], 'supportFriends' => $formData['supportFriends'], 'supportWork' => $formData['supportWork'],'smartGoalNotes' => $formData['smartGoalNotes'], 'smartGoalSpecific' => $formData['smartGoalSpecific'], 'smartGoalMeasurable' => $formData['smartGoalMeasurable'], 'smartGoalAchievable' => $formData['smartGoalAchievable'], 'smartGoalRelevent' => $formData['smartGoalRelevent'], 'smartGoalTime' => $formData['smartGoalTime'], 'goalNotes' => $formData['goalNotes'], 'parq5' => 'completed', 'state' => 'completed');
				
				if($formData['goalHealthWellness'] != '')
					$updateData['goalHealthWellness'] = groupValsToSingleVal($formData['goalHealthWellness']);
				else
					$updateData['goalHealthWellness'] = '';
					
				if($formData['lifestyleImprove'] != '')
					$updateData['lifestyleImprove'] = groupValsToSingleVal($formData['lifestyleImprove']);
				else
					$updateData['lifestyleImprove'] = '';
				
				if($formData['goalWantTobe'] != '')
					$updateData['goalWantTobe'] = groupValsToSingleVal($formData['goalWantTobe']);
				else
					$updateData['goalWantTobe'] = '';
					
				if($formData['goalWantfeel'] != '')
					$updateData['goalWantfeel'] = groupValsToSingleVal($formData['goalWantfeel']);
				else
					$updateData['goalWantfeel'] = '';
					
				if($formData['goalWantHave'] != '')
					$updateData['goalWantHave'] = groupValsToSingleVal($formData['goalWantHave']);
				else
					$updateData['goalWantHave'] = '';
					
				if($formData['motivationImprove'] != '')
					$updateData['motivationImprove'] = groupValsToSingleVal($formData['motivationImprove']);
				else
					$updateData['motivationImprove'] = '';
					
				if(isset($formData['wholeBody']))
					$updateData['wholeBody'] = $formData['wholeBody'];
				else
					$updateData['wholeBody'] = '';

				// if($formData['smart_goal_option'])
				// 	$updateData['smart_goal_option'] = $parq->groupValsToSingleVal($formData['smart_goal_option']);
					
				Parq::where('id', $parqId)->update($updateData);

				$parq->client()->update(['risk_factor'=>Clients::calculateRiskFactor($parq)]);
				
			}
			if(!$isError){
				$msg['status'] = 'updated';
	            $msg['message'] = displayAlert('success|Data has been saved successfully.');
			}
		}
		return json_encode($msg);

	}
	public function getClient() {
		$editId=$_POST['benchmarkId'];
		$clientInfo = array();
		$clientId = $_POST['id'];
		if($editId==""){
		     //$client = Parq::find($clientId);
		     $client=Parq::where('client_id',$clientId)->select('height','weight')->latest()->first();
		     //$parq = $client->parq;
		 }
		else{
			 $client=Benchmarks::where('client_id',$clientId)->where('id', '<>', $editId)->select('height','weight')->latest()->first();
		}
		

		if($client){
			$clientInfo['status'] = "success";
			$clientInfo['height'] = $client->height;
			$clientInfo['weight'] = $client->weight;
		}
		return json_encode($clientInfo);
		
	}


	/**
	 * Save parq version in parqs_version table
	 * @param parq data in array
	 * @return boolean(true/false)
	**/
	protected function saveParqVersion($parqArray){
		
		if(count($parqArray)){
			$timestamp = \Carbon\Carbon::now();
			$parqArray['parq_id'] = $parqArray['id'];
			$parqArray['intensity'] = groupValsToSingleVal($parqArray['intensity']);
			$parqArray['paPerWeek'] = groupValsToSingleVal($parqArray['paPerWeek']);
			$parqArray['paSession'] = groupValsToSingleVal($parqArray['paSession']);
			$parqArray['questionnaire'] = groupValsToSingleVal($parqArray['questionnaire']);
			$parqArray['created_at'] = $timestamp;
			$parqArray['updated_at'] = $timestamp;

			/* Remove parq primary key */
			unset($parqArray['id']);
			
			/* Save New Version */
			ParqVersion::insert($parqArray);
		}
	}
}