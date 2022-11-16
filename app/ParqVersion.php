<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Session;

class ParqVersion extends Model{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $table = 'parqs_version';
	protected $fillable = [
		'parq_id',
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
		'referrer'
	];

    
	public function client(){
		return $this->belongsTo('App\Clients');
	}
	
	public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->join('clients', 'parqs.client_id', '=', 'clients.id')->where('clients.business_id', $bussId)->whereNull('clients.deleted_at');
    }

    static function findParqVersion($parqId, $bussId = 0){
    	if(!$bussId)
			$bussId = Session::get('businessId');
		
        return ParqVersion::whereHas('client', function($query) use ($bussId){
								$query->where('business_id', $bussId);
					})
					->find($parqId);
    }
}
