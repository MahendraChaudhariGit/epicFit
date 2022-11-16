<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Session;

class Parq extends Model{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $table = 'parqs';
	protected $fillable = [
		'client_id',
		'hearUs',
		'referralNetwork',
		'referralId',
		'firstName',
		'lastName',
		'gender',
		'heightUnit',
		'height',
		'weightUnit',
		'weight',
		'dob',
		'age',
		'occupation',
		'contactNo',
		'isAddress',
		'addressline1',
		'addressline2',
		'city',
		'country',
		'addrState',
		'postal_code',
		'timezone',
		'currency',
		'email',
		'ecName',
		'ecRelation',
		'ecNumber',
		'notes',
		'parq1',
		
		'activity',
		'activityOther',
		'frequency',
		'paPerWeek',
		'intensity',
		'paEnjoy',
		'paEnjoyNo',
		'paSession',
		'paIntensity',
		'preferredTraingDays',
		'epNotes',
		'parq2',

		'headInjury',
		'headInjuryNotes',
		'neckInjury',
		'neckInjuryNotes',
		'shoulderInjury',
		'shoulderInjuryNotes',
		'armInjury',
		'armInjuryNotes',
		'handInjury',
		'handInjuryNotes',
		'backInjury',
		'backInjuryNotes',
		'hipInjury',
		'hipInjuryNotes',
		'legInjury',
		'legInjuryNotes',
		'footInjury',
		'footInjuryNotes',
		'ipfhAdditionalNotes',
		'noInjury',
		'allergies',
		'allergiesList',
		'chronicMedication',
		'chronicMedicationList',
		'medicalCondition',
		'medicaNotes',
		'relMedicalCondition',
		'relMedicaNotes',
		'smoking',
		'smokingPerDay',
		'ipfhNotes',
		'parq3',

		'questionnaire',
		'parqNotes',
		'parq4',

		'goalFitnessComponents',
		'goalHealthWellness',
		'headImprove',
		'headImproveNotes',
		'neckImprove',
		'neckImproveNotes',
		'footImprove',
		'footImproveNotes',
		'legImprove',
		'legImproveNotes',
		'handImprove',
		'handImproveNotes',
		'chestImprove',
		'chestImproveNotes',
		'coreImprove',
		'coreImproveNotes',
		'backImprove',
		'backImproveNotes',
		'hipImprove',
		'hipImproveNotes',
		'shouldersImprove',
		'shouldersImproveNotes',
		'armsImprove',
		'armsImproveNotes',
		'wholeBody',
		'smartGoalNotes',
		'smartGoalSpecific',
		'smartGoalMeasurable',
		'smartGoalAchievable',
		'smartGoalRelevent',
		'smartGoalTime',
		'supportFamily',
		'supportFriends',
		'supportWork',
		'goalWantTobe',
		'goalWantfeel',
		'goalWantHave',
		'achieveGoal',
		'lifestyleImprove',
		'motivationImprove',
		'goalNotes',
		'parq5',
		'state',
		'waiverDate',
		'waiverTerms',
		'referrerother',
		'referencewhere',
		'referrer',
		'ref_Name',
		'client_waiver_term',
		'waiver_id',
		'smart_goal_option',
		'trainerwaiverDate'
	];

	public function getReferralNameAttribute(){
		$reffName = '';
		if($this->referralId){
			if($this->referralNetwork == 'Client'){
			 	$cData = Clients::withTrashed()->where('id', $this->referralId)->select('firstname','lastname')->first();
				if(count($cData))
			 		$reffName = $cData->firstname.' '.$cData->lastname;
			}
			elseif($this->referralNetwork == 'Professional network'){
				$reffName = Contact::where('id', $this->referralId)->pluck('company_name')->first(); 
			}
			elseif($this->referralNetwork == 'Staff'){
				$cData = Staff::withTrashed()->where('id', $this->referralId)->select('first_name','last_name')->first();
				if(count($cData))
			 		$reffName = $cData->first_name.' '.$cData->last_name;
			}	
		}		
		return $reffName;
	}

    public function getIntensityAttribute($value){
        return explode(',', $value);
	}
	
	// public function getPaPerWeekAttribute($value){
    //     return explode(',', $value);
    // }

    public function getPaSessionAttribute($value){
        return explode(',', $value);
    }


    public function getMedicalConditionAttribute($value){
        return explode(',', $value);
    }

    public function getRelMedicalConditionAttribute($value){
        return explode(',', $value);
    }

    public function getQuestionnaireAttribute($value){
    	if($value)
		 	return explode(',', $value);
		 return [];
    }

    public function getChestImproveAttribute($value){
		return isset($value)&& $value !='' ?explode(',', $value):[];
	}

    public function getCoreImproveAttribute($value){
		return isset($value)&& $value !='' ?explode(',', $value):[];
	}

	/*public function setDob($year, $month, $day){
		return $year.'-'.$month.'-'.$day;
	}*/

	public function pipeSepVal($valsArr){
		$temp = [];
		foreach($valsArr as $key => $value){
			if(is_array($value))
				$temp[] = implode(',',$value);
			else
				$temp[] = $value;
		}
		return implode('|', $temp);
	}
	
	public function groupValsToSingleVal($valsArr){
		if(is_array($valsArr))
			return implode(',', $valsArr);
		return $valsArr;
	}

	/*public function setAgeAttribute()
	{
		$this->attributes['age'] = (int)date('Y') - date('Y', strtotime($this->attributes['dob']));
	}*/

	/*public function calcAge($dob)
	{
		//dd($dob);
		//return floor((int)date('Y') - date('Y', strtotime($dob)));
		$dt = Carbon::parse($dob);
		$dob=$dt->year;
		if($dob > 0){
			$cage=floor((int)date('Y') - $dob);
			if($cage > 0 )
				return $cage.' Years';
		}
		return 0;
		
	}*/
	
	public function client()
	{
		return $this->belongsTo('App\Clients');
	}
	
	public function setWaiverDateAttribute($date){
		$this->attributes['waiverDate'] = dateStringToDbDate($date)->toDateString();
	}

	public function setTrainerWaiverDateAttribute($date){
		$this->attributes['trainerwaiverDate'] = dateStringToDbDate($date)->toDateString();
	}
	
	public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->join('clients', 'parqs.client_id', '=', 'clients.id')->where('clients.business_id', $bussId)->whereNull('clients.deleted_at');
    }

    static function findParq($parqId, $bussId = 0){
    	if(!$bussId)
			$bussId = Session::get('businessId');
		
        return Parq::whereHas('client', function($query) use ($bussId){
								$query->where('business_id', $bussId);
					})
					->find($parqId);
    }
}
