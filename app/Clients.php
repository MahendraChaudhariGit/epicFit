<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Session;
use DB;
use App\CalendarSetting;

class Clients extends Model{
    use SoftDeletes;

    const LDC_ACTIVE = 1, LDC_INACTIVE = 0;
    const ALLOWED_LDC_CLASS = 2, ALLOWED_CLASS_CAT = "Class Type 1";

    protected $table = 'clients';
    protected $dates = ['deleted_at'];
    //protected $appends = array('risk_factor');
	protected $fillable = [
		'birthday',
		'firstname', 
		'email', 
		'lastname',
		'phonenumber',
		'gender',
		'occupation',
		'country',
		'account_status',
        'sale_process_setts',
        'sale_process_step',
		'profilepic',
		'business_id',
		'note_id',
        'login_with_email',
        'email_to_client',
        'consultation_date',
        'consul_exp_date',
        'consul_exp_duration',
        'consul_exp_type',
    	'address1',
        'health_wellness',
        'increased_energy',
        'tone',
        'injury_recovery',
        'improved_nutrition',
        'lose_weight',
        'improved_performance',
        'improved_endurance',
        'strength_conditioning',
        'activity',
        'epic_credit_balance',
        'risk_factor',
        'cn_notes',
        'ldc_status',
        'ldc_session_id',
        'unit',
        'address_city',
        'about_me',
        'privacy',
        'cover_image'
	];

    public function getFullNameAttribute(){
        return $this->firstname.' '.$this->lastname;
    }
    
    public function getRiskFactorrAttribute(){
        return $this->calculateRiskFactor($this->parq);  
    }
    public function getAllNetAmountAttribute(){
        //return $this->makeups()->sum('makeup_amount');
        return $this->epic_credit_balance;
    }

    public function getAllRiseMakeupAttribute(){
        return $this->makeups()->sum('makeup_session_count');
    }

    public function getSaleProcessSettAttribute(){
        $calendarSettg = CalendarSetting::where('cs_business_id',Session::get('businessId'))->first();
        if($this->sale_process_setts)
            return json_decode($this->sale_process_setts,1);
        elseif($calendarSettg && $calendarSettg->sales_process_settings){
            return json_decode($calendarSettg->sales_process_settings,1);
        }
        return [];
    }

    public function getTeamEnabledCountAttribute(){
        $setts = $this->SaleProcessSett;
        $teamCount = $setts['teamCount'];
        if($teamCount)
            return $teamCount;
        return 0;
    }

    public function getIndivEnabledCountAttribute(){
        $setts = $this->SaleProcessSett;
        $indivCount = $setts['indivCount'];
        if($indivCount)
            return $indivCount;
        return 0;
    }

    public function getSalesSessionOrderAttribute(){
        $setts = $this->SaleProcessSett;
        $order = [];
        if(count($setts['order'])){
            foreach($setts['order'] as $key => $value){
                $order[] = $value['id'];
            }
        }
        return $order;
    }

    public function getSaleProcessEnabledStepsAttribute(){
        $steps = [1,2,3];
        $setts = $this->SaleProcessSett;
        if(count($setts)){
            foreach($setts['steps'] as $step)
                $steps[] = (int) $step;

            $steps = array_merge($steps, $setts['session']);
            $steps = array_unique($steps);
            sort($steps);
        }
        return $steps;
    }

    public function getSaleProcessEnabledAttendStepsAttribute(){
        $steps = [];
        $enabledSteps = $this->SaleProcessEnabledSteps;
        if(count($enabledSteps)){
            $salesAttendanceSteps = salesAttendanceSteps();
            foreach($enabledSteps as $enabledStep){
                $stepDetails = calcSalesProcessRelatedStatus((int) $enabledStep);
                if(in_array($stepDetails['salesProcessType'], $salesAttendanceSteps)/* || in_array($enabledStep, $teamAttendSteps) || in_array($enabledStep, $indivAttendSteps)*/)
                    $steps[] = $enabledStep;
            }
        }
        return $steps;
    }

    public function getSalesCompStepsAttribute(){
        if($this->salesProgress->count()){
            $compSteps = $this->salesProgress->pluck('spp_step_numb')->toArray();
            return json_encode($compSteps);
        }
        return '';
    }

    /*static function calcRiskFactor(){
        return Clients::calculateRiskFactor();
    }*/
    static function calculateRiskFactor($parq){
        /*
            $parq = $this->parq;
         */
        //dd($parq);
        if($parq && $parq->parq1 == 'completed'){
            $riskFactor = 0;

            if(($parq->gender == 'Male' && $parq->age > 45) || ($parq->gender == 'Female' && $parq->age > 55))
                $riskFactor++;
            
            if($parq->paPerWeek != '' && $parq->intensity != ''){
                $duration = 0;
                if($parq->paPerWeek != ''){
                    if(in_array('vigorous', $parq->intensity) || in_array('high', $parq->intensity) && !in_array($parq->paPerWeek, array('30 min','60 min')))
                        $duration = 150;
                    else if($parq->paPerWeek == '150 min +')
                        $duration = 150;
                }
                if($duration < 150)
                    $riskFactor++;
                else
                    $riskFactor--;
            }
            
            if(in_array('High/Low blood pressure under medication', $parq->medicalCondition))
                $riskFactor++;
            if(in_array('Stroke', $parq->medicalCondition) && (($parq->gender == 'Male' && $parq->age > 55) || ($parq->gender == 'Female' && $parq->age > 65)))
                $riskFactor++;
            if(in_array('High cholesterol', $parq->medicalCondition))
                $riskFactor++;
            if(in_array('Heart conditions', $parq->medicalCondition) && (($parq->gender == 'Male' && $parq->age > 55) || ($parq->gender == 'Female' && $parq->age > 65)))
                $riskFactor++;
            if(in_array('High/Low blood pressure under medication', $parq->relMedicalCondition))
                $riskFactor++;
            if(in_array('Stroke', $parq->relMedicalCondition) && (($parq->gender == 'Male' && $parq->age > 55) || ($parq->gender == 'Female' && $parq->age > 65)))
                $riskFactor++;
            if(in_array('High cholesterol', $parq->relMedicalCondition))
                $riskFactor++;
            if(in_array('Heart conditions', $parq->relMedicalCondition) && (($parq->gender == 'Male' && $parq->age > 55) || ($parq->gender == 'Female' && $parq->age > 65)))
                $riskFactor++;
            if($parq->smoking == 'Yes' && $parq->smokingPerDay == '20+')
                $riskFactor++;
            
            if(in_array('ansYes4', $parq->questionnaire))
                $riskFactor++;
        }
        else
            $riskFactor = "To be calculated";

        return $riskFactor;
    }

    public function user(){
        return $this->hasOne('App\Models\Access\User\User', 'account_id')->whereAccountType('Client');
    }
	
    public function users(){
        return $this->belongsToMany('App\User','client_user','client_id','user_id');
    }

    public function parq(){
        return $this->hasOne('App\Parq','client_id');
    }

    /* Start: client menues */
    public function clientMenu(){
        return $this->hasOne('App\ClientMenu', 'client_id');
    }
    /* End: client menues */

    public function parqWithTrashed(){
        return $this->parq()->withTrashed();
    }

	public function benchmarks(){
        return $this->hasMany('App\Benchmarks','client_id')->orderBy('created_at','DESC');
    }

	/*public function notes(){
		return $this->hasMany('App\ContactNotes','client_id');
	}*/
    public function contactNotes(){
        return $this->hasMany('App\ContactNotes','client_id');
    }

    public function salesProcessLog(){
        return $this->hasMany('App\SalesProcess','sp_client_id');
    }

    public function salesProcesses(){
        return $this->salesProcessLog()->where('sp_action', 'upgrade')->latest();
    }

	public function business(){
        return $this->belongsTo('App\Business');
    }

    public function credits(){
        return $this->hasMany('App\ClientCredit', 'cc_client_id');
    }

    public function events(){
        return $this->hasMany('App\StaffEventSingleService', 'sess_client_id');
    }

    public function notes() {
        return $this->hasMany('App\ContactNotes', 'client_id');
    }

    public function clientEventClasses($date){
        return $this->eventClassesList()->whereNull('staff_event_classes.deleted_at')->whereNull('staff_event_class_clients.deleted_at')->where('sec_date','=' ,$date);
    }

    public function clientEventService($date){
        return $this->appointmentsList()->whereNull('deleted_at')->where('sess_date','=' ,$date);
    }

    public function futureEvents(){
        $now = new Carbon();
        $date = setLocalToBusinessTimeZone($now);    
        return $this->events()->where('sess_start_datetime', '>=', $date->toDateTimeString());
    }

    public function makeups(){
        return $this->hasMany('App\Makeup', 'makeup_client_id');
    } 

    private function appointmentsList(){
        return $this->events()
                    ->with(array('staffWithTrashed' => function($query){ 
                            $query->select('id', 'first_name', 'last_name');
                      }, 'serviceWithTrashed')) 
                    ->withTrashed()
                    /*->where('sess_deleted_in_chain', 0)*/
                    ->select('sess_id', 'sess_date', 'sess_date as eventDate', 'sess_epic_credit', 'sess_sessr_id', 'deleted_at', 'sess_booking_status', 'sess_staff_id', 'sess_service_id', 'sess_time', 'sess_client_attendance', 'sess_time as eventTime', 'sess_cmid', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up','sess_with_invoice', 'sess_price','sess_start_datetime');
                    // ->whereNull('deleted_at');//, 'se_booking_status_confirm'             
    }
    
    public function pastAppointments(){   
        $now = new Carbon();
        $date = setLocalToBusinessTimeZone($now);              
        return $this->appointmentsList()
                    ->where(function($query) use ($date){
                            $query->where('sess_date', '<', $date->toDateString())
                                  ->orWhere(function($query) use ($date){
                                        $query->where('sess_date', '=', $date->toDateString())
                                              ->where('sess_time', '<=', $date->toTimeString());
                                    });
                      })
                    ->orderBy('sess_date','DESC')
                    ->orderBy('sess_time','DESC');
    }

    public function futureAppointments(){        
        $now = new Carbon();
        $date = setLocalToBusinessTimeZone($now);              
        return $this->appointmentsList()
                    ->where(function($query) use ($date){
                            $query->where('sess_date', '>', $date->toDateString())
                                  ->orWhere(function($query) use ($date){
                                        $query->where('sess_date', '=', $date->toDateString())
                                              ->where('sess_time', '>', $date->toTimeString());
                                    });
                      })
                    ->orderBy('sess_date')
                    ->orderBy('sess_time');
    }

    private function eventClassesList(){
        return $this->eventClasses() //WithTrashed - removed by pk as showing appointments to removed class clients
                    ->with(array('staffWithTrashed' => function($query){ 
                            $query->select('id', 'first_name', 'last_name');
                      }, 'clasWithTrashed' => function($query){ 
                            $query->select('cl_id', 'cl_name', 'cl_colour');
                      })) 
                    ->withTrashed()
                    /*->where('sec_deleted_in_chain', 0)*/
                    ->select('sec_id', 'sec_date', 'sec_date as eventDate', 'sec_secr_id', 'staff_event_classes.deleted_at', 'sec_staff_id', 'sec_class_id', 'sec_time', 'sec_time as eventTime', 'sec_price'/*, 'secc_cmid'*/, 'sec_start_datetime', 'sec_end_datetime','sec_capacity');
    }

    public function pastClasses(){   
        $now = new Carbon();
        $date = setLocalToBusinessTimeZone($now);             
        return $this->eventClassesList()
                    ->where(function($query) use ($date){
                            $query->where('sec_date', '<', $date->toDateString())
                                  ->orWhere(function($query) use ($date){
                                        $query->where('sec_date', '=', $date->toDateString())
                                              ->where('sec_time', '<=', $date->toTimeString());
                                    });
                      })
                    ->orderBy('sec_date','DESC')
                    ->orderBy('sec_time','DESC');
    }

    public function futureClasses(){   
        $now = new Carbon(); 
        $date = setLocalToBusinessTimeZone($now);             
        return $this->eventClassesList()
                    ->where(function($query) use ($date){
                            $query->where('sec_date', '>', $date->toDateString())
                                  ->orWhere(function($query) use ($date){
                                        $query->where('sec_date', '=', $date->toDateString())
                                              ->where('sec_time', '>', $date->toTimeString());
                                    });
                      })
                    ->orderBy('sec_date')
                    ->orderBy('sec_time')
                    /*->orderBy('secc_if_make_up')
                    ->orderBy('secc_if_make_up_created', 'desc')*/;
    }

    public function eventClassesWithTrashed(){
        return $this->belongsToMany('App\StaffEventClass', 'staff_event_class_clients', 'secc_client_id', 'secc_sec_id')->withPivot('secc_notes', 'secc_epic_credit', 'secc_reduce_rate_session', 'secc_reduce_rate', 'secc_if_recur', 'secc_client_attendance', 'secc_client_status'/*, 'secc_if_make_up', 'secc_if_make_up_created'*/, 'secc_id', 'secc_cmid','secc_with_invoice', 'secc_invoice_status', 'deleted_at', 'secc_event_log', 'secc_if_make_up_created','is_ldc','secc_class_extra');
    }

    public function eventClasses(){
        // return $this->eventClassesWithTrashed()->whereNull('staff_event_class_clients.deleted_at');
        return $this->eventClassesWithTrashed();
    }

    public function eventClassesWithoutTrashed() {
        return $this->belongsToMany('App\StaffEventClass', 'staff_event_class_clients', 'secc_client_id', 'secc_sec_id')->whereNull('staff_event_classes.deleted_at')->whereNull('staff_event_class_clients.deleted_at')->withPivot('secc_notes', 'secc_epic_credit', 'secc_reduce_rate_session', 'secc_reduce_rate', 'secc_if_recur', 'secc_client_attendance', 'secc_client_status'/*, 'secc_if_make_up', 'secc_if_make_up_created'*/, 'secc_id', 'secc_cmid','secc_with_invoice', 'secc_invoice_status', 'deleted_at');
    }

    static function nextMembership($clientId){
        /*$record = ClientMember::where('cm_client_id', $clientId)->latest()->first();
        if($record && $record->cm_status == 'Next')
            return $record;
        return false;*/
        return ClientMember::where('cm_client_id', $clientId)->where('cm_status', 'Next')->latest()->orderBy('id', 'desc')->first();
    }

    static function membership($clientId){
        $record = ClientMember::where('cm_client_id', $clientId)->where('cm_status', '!=', 'Next')->latest()/*->orderBy('id', 'desc')*/->first();
      
        if($record && $record->cm_status != 'Removed')
            return $record;
        return false;
    }

    static function paidMembership($clientId){
        $record = Clients::membership($clientId);
        if($record && $record->cm_status == 'Active')
            return $record;
        return false;
    }
    static function clientMembershipType($clientId){
        $record=Clients::membership($clientId);
        if($record){
            if($record->cm_status == 'Active')
                return $record->cm_label;
            elseif($record->cm_status == 'On Hold') {
                return "<span class='text-danger'>".$record->cm_label."</span>";
            }
        }
        return '----';
        /*$record = $records->where('cm_status', '!=', 'Next')->first();
        if($record && $record->cm_status != 'Removed')
            return $record;
        return false;*/
    }
    public function note(){
        return $this->belongsTo('App\ClientNote','note_id','cn_id');
    }
    /*
    public function getMembershipAttribute(){
        return $this->memberships()->latest()->first();
    }*/

    public function memberships(){
        return $this->hasMany('App\ClientMember', 'cm_client_id');
    }

    public function salesProgress(){
        return $this->hasMany('App\SalesProcessProgress', 'spp_client_id');
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($client){
            /* Deleting benchmarks */
            $client->benchmarks()->delete();

            /* Deleting parq */
            $client->parq()->delete();

            /* Deleting contact notes */
            $client->contactNotes()->delete();

            /* Deleting sales process */
            $client->salesProcessLog()->delete();
            $client->salesProgress()->delete();

            /* Deleting credits */
            $client->credits()->delete();

            /* Deleting memberships */
            $client->memberships()->delete();

            /* Deleting login credentials */
            if($client->login_with_email)
                $client->user()->delete();

            /* Deleting future event appointments */
            //$client->futureEvents()->update(['se_client_deleted' => 1]);
            $client->futureEvents()->update(['sess_client_deleted' => 1]);
            foreach($client->futureEvents as $event)
                $event->delete();

            /* Deleting linkage from future event classes */
            /*$eventClasses = $client->eventClasses;
            if($eventClasses->count()){
                $pastPivotIds = $futurePivotIds = [];
                $now = new Carbon();
                foreach($eventClasses as $eventClass){
                    $eventDate = new Carbon($eventClass->sec_start_datetime);
                    if($eventDate->gte($now))
                        $futurePivotIds[] = $eventClass->pivot->secc_id;
                    else
                        $pastPivotIds[] = $eventClass->pivot->secc_id;
                }

                if(count($futurePivotIds))
                    DB::table('staff_event_class_clients')->whereIn('secc_id', $futurePivotIds)->update(['deleted_at' => createTimestamp(), 'secc_client_deleted' => 1]);

                if(count($pastPivotIds))
                    DB::table('staff_event_class_clients')->whereIn('secc_id', $pastPivotIds)->update(['secc_client_deleted' => 1]);
            }*/
            $eventClasses = $client->eventClassesWithTrashed;
            if($eventClasses->count()){
                $pastPivotIds = $futurePivotIds = [];
                $now = new Carbon();
                foreach($eventClasses as $eventClass){
                    $eventDate = new Carbon($eventClass->sec_start_datetime);
                    if($eventClass->trashed() || $eventDate->lt($now))
                        $pastPivotIds[] = $eventClass->pivot->secc_id;
                    else
                        $futurePivotIds[] = $eventClass->pivot->secc_id;
                }

                if(count($futurePivotIds))
                    DB::table('staff_event_class_clients')->whereIn('secc_id', $futurePivotIds)->update(['deleted_at' => createTimestamp(), 'secc_client_deleted' => 1]);

                if(count($pastPivotIds))
                    DB::table('staff_event_class_clients')->whereIn('secc_id', $pastPivotIds)->update(['secc_client_deleted' => 1]);
            }

            /*$now = new Carbon();
            $eventClasses = $client->eventClasses()->where('sec_start_datetime', '>=', $now->toDateTimeString())->get();
            if($eventClasses->count()){
                $pivotIds = [];
                foreach($eventClasses as $eventClass)
                    $pivotIds[] = $eventClass->pivot->secc_id;

                if(count($pivotIds))
                    DB::table('staff_event_class_clients')->whereIn('secc_id', $pivotIds)->update(['deleted_at' => createTimestamp(), 'secc_client_deleted' => 1]);
            }*/
        });
        static::deleted(function(){
            if(!Clients::OfBusiness()->exists())
                Session::forget('ifBussHasClients');
        });
    }

    public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->where('business_id', $bussId);
    }

    static function findClient($clientId, $bussId = 0){
        return Clients::OfBusiness($bussId)->find($clientId);
    }

    static function findOrFailClient($clientId, $bussId = 0){
        return Clients::OfBusiness($bussId)->findOrFail($clientId);
    }

    public function scopeHavingString($query, $search){
        $search = trim($search);
        return $query->where(function($query) use ($search){
            $parts = explode(" ", $search);
            $query->orWhere('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->orWhere('phonenumber', 'like', "%$search%")->orWhere(function($query) use ($parts){
                    if(count($parts)>1)
                        $query->where('firstname', 'like', "%{$parts[0]}%")->where('lastname', 'like', "%{$parts[1]}%");
                });
            });
    }

    public function scopeHavingStatus($query, $status){
        return $query->where('account_status', $status);
    }

    public function futureExtraSessions(){   
        $now = new Carbon(); 
        $date = setLocalToBusinessTimeZone($now);             
        return $this->eventExtraSessionsList()
                    ->where(function($query) use ($date){
                            $query->where('sec_date', '>', $date->toDateString())
                                  ->orWhere(function($query) use ($date){
                                        $query->where('sec_date', '=', $date->toDateString())
                                              ->where('sec_time', '>', $date->toTimeString());
                                    });
                      })->where('secc_class_extra', 1)
                    ->orderBy('sec_date')
                    ->orderBy('sec_time')
                    /*->orderBy('secc_if_make_up')
                    ->orderBy('secc_if_make_up_created', 'desc')*/;
    }
    private function eventExtraSessionsList(){
        return $this->eventClasses() //WithTrashed - removed by pk as showing appointments to removed class clients
                    ->with(array('staffWithTrashed' => function($query){ 
                            $query->select('id', 'first_name', 'last_name');
                      }, 'clasWithTrashed' => function($query){ 
                            $query->select('cl_id', 'cl_name', 'cl_colour');
                      })) 
                    ->withTrashed()
                    /*->where('sec_deleted_in_chain', 0)*/
                    ->select('sec_id', 'sec_date', 'sec_date as eventDate', 'sec_secr_id', 'staff_event_classes.deleted_at', 'sec_staff_id', 'sec_class_id', 'sec_time', 'sec_time as eventTime', 'sec_price'/*, 'secc_cmid'*/, 'sec_start_datetime', 'sec_end_datetime','sec_capacity');
    }

    public function social_friend(){
        return $this->hasOne('App\SocialFriend','added_client_id','id')->with(['clients_recieve_request']);
    }

    public function recieve_friend(){
        return $this->hasOne('App\SocialFriend','client_id','id')->with(['clients_recieve_request']);
    }

    public function all_friends(){
        return $this->hasMany('App\SocialFriend','client_id','id')->where('status','Accepted')->with(['clients']);
    }

    public function request_recieve(){
        return $this->hasMany('App\SocialFriend','added_client_id','id')->where('status','No Action')->with(['clients_recieve_request']);
    }

    public function request_send(){
        return $this->hasMany('App\SocialFriend','client_id','id')->where('status','No Action')->with(['clients']);
    }
}