<?php 
namespace App\Http\Controllers\Result;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Clients;
use Auth;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\ClientTrait;
use DB;
use Carbon\Carbon;

class ParqController extends Controller {
	use HelperTrait, ClientTrait;

    public function index(){
        $user = \Illuminate\Support\Facades\Auth::user();
	}
	
	public function waiverSave(Request $request)
	{
		$formData = $request->all();
		$clientId = Auth::user()->account_id;
		$client = Clients::find($clientId);
		$parq = $client->parq;
		$waiverdate = dbDateToDateString($formData['waiverDate']);
		$parq->waiverDate = $waiverdate;
		if(isset($formData['client_waiver_term']))
			$parq->client_waiver_term = $formData['client_waiver_term'];
			
		if($parq->update())	
			return 'true';

		return 'false';
	}

	public function parqSave(Request $request){
		
		$formData = $request->all()['formData'];
		ksort($formData);
		
		$clientId = (int)$formData['client_id'];
		$client = Clients::find($clientId);
		$parq = $client->parq;
		$isError = false;
		$msg = [];
		if(!$isError){
			if((int)$formData['stepNumb'] == 1){
		        if(!$isError){
		        	if($formData['referrer']=="socialmedia"){
		            	$formData['otherName'] = $formData['referencewhere'];
		            }
		            else{
		            	$formData['otherName'] = '';
		            }

		        	if($formData['referrer']!="onlinesocial" && $formData['referrer']!="mediapromotions" ){
		            	$formData['referencewhere'] = '';
		            }

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

					$updateData = array('firstName' => $formData['firstName'], 'lastName' => $formData['lastName'], 'hearUs' => $formData['referrer'], 'referencewhere'=>$formData['referencewhere'], 'referrerother'=>$formData['otherName'], 'heightUnit' => $formData['heightUnit'], 'height' => $height, 'weightUnit' => $formData['weightUnit'], 'weight' => $weight , 'dob' => prepareDob($formData['yyyy'], $formData['mm'], $formData['dd']), 'occupation' => $formData['occupation'],'contactNo' => $formData['contactNo'], 'ecName' => $formData['ecName'], 'ecRelation' => $formData['ecRelation'], 'ecNumber' => $formData['ecNumber'], 'ref_Name' => $formData['ReferralName'], 'parq1' => 'completed');

					if(isset($formData['notes'])){
						$updateData['notes'] = $formData['notes'];
					}

					$updateData['age'] = $this->calcAge($updateData['dob']);
					
					if(isset($formData['referralNetwork'])){
						$updateData['referralNetwork'] = $formData['referralNetwork'];
						//$updateData['referralId'] = $formData['clientId'];
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
						
						
					$parq->update($updateData);	

					$dataForClients = array('firstname' => $formData['firstName'], 'lastname' => $formData['lastName'], 'birthday' => $updateData['dob'], 'occupation' => $formData['occupation'], 'contactNo' => $formData['contactNo']);
					
					if(isset($formData['gender']))
						$dataForClients['gender'] = $formData['gender'];
						
					if(isset($formData['addressline1']) && $formData['addressline1'] != '')
						$dataForClients['country'] = $formData['country'];
					else
						$dataForClients['country'] = '';
                    if($formData['clientpic'] == '' && $formData['prePhotoName'] != ''){
						$dataForClients['profilepic'] = '';
					}
					elseif(isset($formData['clientpic']) && $formData['clientpic'] != '')
						$dataForClients['profilepic'] = $formData['clientpic'];
					elseif(isset($formData['prePhotoName']) && $formData['prePhotoName'] != '')
						$dataForClients['profilepic'] = $formData['prePhotoName'];
					
					$dataForClients['risk_factor'] = Clients::calculateRiskFactor($parq);

					$client->update($dataForClients);

					Auth::user()->update(['name'=>$formData['firstName'], 'last_name' =>$formData['lastName']]);	
				}		
			}
			else if((int)$formData['stepNumb'] == 2){
				$updateData = array('activity' => $formData['activity'], 'activityOther' => $formData['activityOther'], 'frequency' => $formData['frequency'], 'paPerWeek' => $formData['paPerWeek'], 'paEnjoy' => $formData['paEnjoy'], 'paEnjoyNo' => $formData['paEnjoyNo'], 'paSession' => $formData['paSession'], 'preferredTraingDays' => $formData['preferredTraingDays']/*$parq->pipeSepVal($days)*/,'parq2' => 'completed');

				if(isset($formData['epNotes'])){
					$updateData['epNotes'] = $formData['epNotes'];
				}
				
				if($formData['intensity'] != '')
					$updateData['intensity'] = $parq->groupValsToSingleVal($formData['intensity']);
				else
					$updateData['intensity'] = '';
				
				if($formData['paIntensity'] != '')
					$updateData['paIntensity'] = $parq->groupValsToSingleVal($formData['paIntensity']);
				else
					$updateData['paIntensity'] = '';

				$parq->update($updateData);
				$client->update(['risk_factor'=>Clients::calculateRiskFactor($parq)]);
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
					$updateData = array('headInjury' => '', 'neckInjury' => '', 'shoulderInjury' => '', 'armInjury' => '', 'handInjury' => '', 'backInjury' => '', 'hipInjury' => '', 'legInjury' => '', 'footInjury' => '', 'headInjuryNotes' => '', 'neckInjuryNotes' => '', 'backInjuryNotes' => '', 'footInjuryNotes' => '', 'legInjuryNotes' => '', 'hipInjuryNotes' => '', 'shoulderInjuryNotes' => '', 'armInjuryNotes' => '', 'handInjuryNotes' => ''/*, 'ipfhAdditionalNotes' => '','allergies' => $formData['allergies'], 'chronicMedication' => $formData['chronicMedication'], 'medicaNotes' => '', 'relMedicaNotes' =>'', 'smoking' => $formData['smoking'], 'smoking' => $formData['smoking'], 'ipfhNotes' => '', 'parq3' => 'completed'*/);
				} 
				else{
					$updateData = array('headInjury' => $parq->groupValsToSingleVal($headInjury), 'neckInjury' => $parq->groupValsToSingleVal($neckInjury), 'shoulderInjury' => $parq->groupValsToSingleVal($shoulderInjury), 'armInjury' => $parq->groupValsToSingleVal($armInjury), 'handInjury' => $parq->groupValsToSingleVal($handInjury), 'backInjury' => $parq->groupValsToSingleVal($backInjury), 'hipInjury' => $parq->groupValsToSingleVal($hipInjury), 'legInjury' => $parq->groupValsToSingleVal($legInjury), 'footInjury' => $parq->groupValsToSingleVal($footInjury), 'headInjuryNotes' => $formData['notesHeadInjury'], 'neckInjuryNotes' => $formData['notesNeckInjury'], 'backInjuryNotes' => $formData['notesBackInjury'], 'footInjuryNotes' => $formData['notesFootInjury'], 'legInjuryNotes' => $formData['notesLegInjury'], 'hipInjuryNotes' => $formData['notesHipInjury'], 'shoulderInjuryNotes' => $formData['notesShoulderInjury'], 'armInjuryNotes' => $formData['notesArmInjury'], 'handInjuryNotes' => $formData['notesHandInjury']/*, 'ipfhAdditionalNotes' => $formData['ipfhAdditionalNotes'], 'allergies' => $formData['allergies'], 'chronicMedication' => $formData['chronicMedication'], 'medicaNotes' => $formData['medCondNotes'], 'relMedicaNotes' => $formData['relMedCondNotes'], 'smoking' => $formData['smoking'], 'smoking' => $formData['smoking'], 'ipfhNotes' => $formData['ipfhNotes'], 'parq3' => 'completed'*/);
				}

				
				$updateData['ipfhAdditionalNotes'] = $formData['ipfhAdditionalNotes'];
				$updateData['allergies'] = $formData['allergies'];
				$updateData['chronicMedication'] = $formData['chronicMedication'];
				$updateData['medicaNotes'] = $formData['medCondNotes'];
				$updateData['relMedicaNotes'] = $formData['relMedCondNotes'];
				$updateData['smoking'] = $formData['smoking'];
				if(isset($formData['ipfhNotes'])){
					$updateData['ipfhNotes'] = $formData['ipfhNotes'];
				}
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
					$updateData['medicalCondition'] = $parq->groupValsToSingleVal($formData['medicalCondition']);
				else
					$updateData['medicalCondition'] = '';
				
				if($formData['relMedicalCondition'] != '')
					$updateData['relMedicalCondition'] = $parq->groupValsToSingleVal($formData['relMedicalCondition']);
				else
					$updateData['relMedicalCondition'] = '';
				
				if(isset($formData['noInjury']))
					$updateData['noInjury'] = $formData['noInjury'];
				else
					$updateData['noInjury'] = '';
					
				$parq->update($updateData);
				$client->update(['risk_factor'=>Clients::calculateRiskFactor($parq)]);
			}
			else if((int)$formData['stepNumb'] == 4){
				$ans = [];
				foreach($formData as $key => $value){
					if(strpos($key, 'ans') !== false)
						$ans[] = $value;
				}
				
				$updateData = array('questionnaire' => $parq->groupValsToSingleVal($ans), 'parq4' => 'completed');
				if(isset($formData['parqNotes'])){
					$updateData['parqNotes'] = $formData['parqNotes'];
				}

				$parq->update($updateData);
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
				
				$updateData = array('goalFitnessComponents' => $parq->pipeSepVal($components), 'headImprove' => $parq->groupValsToSingleVal($headImprove), 'neckImprove' => $parq->groupValsToSingleVal($neckImprove), 'footImprove' => $parq->groupValsToSingleVal($footImprove), 'legImprove' => $parq->groupValsToSingleVal($legImprove), 'handImprove' => $parq->groupValsToSingleVal($handImprove), 'chestImprove' => $parq->groupValsToSingleVal($chestImprove), 'coreImprove' => $parq->groupValsToSingleVal($coreImprove), 'backImprove' => $parq->groupValsToSingleVal($backImprove), 'hipImprove' => $parq->groupValsToSingleVal($hipImprove), 'shouldersImprove' => $parq->groupValsToSingleVal($shouldersImprove), 'armsImprove' => $parq->groupValsToSingleVal($armsImprove), 'headImproveNotes' => $formData['notesHeadImprove'], 'neckImproveNotes' => $formData['notesNeckImprove'], 'backImproveNotes' => $formData['notesBackImprove'], 'footImproveNotes' => $formData['notesFootImprove'], 'legImproveNotes' => $formData['notesLegImprove'], 'hipImproveNotes' => $formData['notesHipImprove'], 'shouldersImproveNotes' => $formData['notesShouldersImprove'], 'armsImproveNotes' => $formData['notesArmsImprove'], 'handImproveNotes' => $formData['notesHandImprove'], 'chestImproveNotes' => $formData['notesChestImprove'], 'coreImproveNotes' => $formData['notesCoreImprove'], 'achieveGoal' => $formData['achieveGoal'], 'supportFamily' => $formData['supportFamily'], 'supportFriends' => $formData['supportFriends'], 'supportWork' => $formData['supportWork'],'smartGoalNotes' => $formData['smartGoalNotes'], 'smartGoalSpecific' => $formData['smartGoalSpecific'], 'smartGoalMeasurable' => $formData['smartGoalMeasurable'], 'smartGoalAchievable' => $formData['smartGoalAchievable'], 'smartGoalRelevent' => $formData['smartGoalRelevent'], 'smartGoalTime' => $formData['smartGoalTime'], 'goalNotes' => $formData['goalNotes'], 'parq5' => 'completed', 'state' => 'completed');
				
				if($formData['goalHealthWellness'] != '')
					$updateData['goalHealthWellness'] = $parq->groupValsToSingleVal($formData['goalHealthWellness']);
				else
					$updateData['goalHealthWellness'] = '';
					
				if($formData['lifestyleImprove'] != '')
					$updateData['lifestyleImprove'] = $parq->groupValsToSingleVal($formData['lifestyleImprove']);
				else
					$updateData['lifestyleImprove'] = '';
				
				if($formData['goalWantTobe'] != '')
					$updateData['goalWantTobe'] = $parq->groupValsToSingleVal($formData['goalWantTobe']);
				else
					$updateData['goalWantTobe'] = '';
					
				if($formData['goalWantfeel'] != '')
					$updateData['goalWantfeel'] = $parq->groupValsToSingleVal($formData['goalWantfeel']);
				else
					$updateData['goalWantfeel'] = '';
					
				if($formData['goalWantHave'] != '')
					$updateData['goalWantHave'] = $parq->groupValsToSingleVal($formData['goalWantHave']);
				else
					$updateData['goalWantHave'] = '';
					
				if($formData['motivationImprove'] != '')
					$updateData['motivationImprove'] = $parq->groupValsToSingleVal($formData['motivationImprove']);
				else
					$updateData['motivationImprove'] = '';
					
				if(isset($formData['wholeBody']))
					$updateData['wholeBody'] = $formData['wholeBody'];
				else
					$updateData['wholeBody'] = '';

				// if($formData['smart_goal_option'])
				// 	$updateData['smart_goal_option'] = $parq->groupValsToSingleVal($formData['smart_goal_option']);
				

				$parq->update($updateData);
				$client->update(['risk_factor'=>Clients::calculateRiskFactor($parq)]);
			}
			if(!$isError){
				$msg['status'] = 'updated';
	            // $msg['message'] = displayAlert('success|Data has been saved successfully.');
			}
		}
		return json_encode($msg);
	}

}