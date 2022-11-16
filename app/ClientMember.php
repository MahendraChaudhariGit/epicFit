<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ClientMember extends Model{
    use SoftDeletes;
    
    protected $table = 'client_membership';
    protected $fillable = [
	    'cm_client_id',
	    'cm_membership_id',
		'cm_label',
		'cm_validity_length',
		'cm_validity_type',
		'cm_class_limit',
		'cm_class_limit_length',
		'cm_class_limit_type',
		'cm_auto_renewal',
		'cm_pay_plan',
		'cm_renw_amount',
		//'cm_total_amount',
		//'cm_renw_date',
		'cm_due_date',
		'cm_services',
		'cm_services_limit',
		'cm_classes',
		'cm_enrollment_limit',
		'cm_subscription_type',
		'cm_status',
	    'cm_start_date',
	    'cm_end_date',
		'cm_payment_option',
		'cm_cancelled_by',
		'cm_disc_amnt_type',
		'cm_disc_per_class_amnt',
		'cm_disc_percentage',
		'cm_per_clas_amnt',
		'cm_next_emi',
		'cm_emi',
		'cm_original_price'
    ];
	protected $dates = ['cm_due_date', 'cm_end_date'];


	/*
    **start: RELATIONS
    */
	    public function clientmembership(){
	        return $this->hasMany('App\MemberShip', 'id', 'cm_membership_id');
	    }

	    public function events(){
	        return $this->hasMany('App\StaffEventSingleService', 'sess_cmid');
	    }

	   	public function eventClassesWithTrashedpivot(){
	        return $this->belongsToMany('App\StaffEventClass', 'staff_event_class_clients', 'secc_cmid', 'secc_sec_id')->withPivot('secc_id');
	    }

	    public function eventClasses(){
	        return $this->eventClassesWithTrashedpivot()->whereNull('staff_event_class_clients.deleted_at');
	    }

	    public function client(){
	        return $this->belongsTo('App\Clients','cm_client_id','id');
	    }

	/*
    **end: RELATIONS
    */


    /*
    **start: FUNCTIONS
    */
	    /*public function pastAppointments(){   
	        $now = new Carbon();              
	        return $this->appointmentsList()
	        			->where('sess_start_datetime', '<', $now->toDateTimeString());
	    }*/

	    public function futureAppointments(){        
	        $now = new Carbon();              
	        //return $this->appointmentsList()
	        return $this->events()
	        			->select('sess_id','sess_date','sess_client_id','sess_service_id')
	        			->where('sess_start_datetime', '>=', $now->toDateTimeString());
	    }

	    /*private function appointmentsList(){
	        return $this->events()
	                    ->select('sess_id');
	    }*/

	    /*public function chargeableClasses(){   
	        $now = new Carbon();              
	        return $this->eventClassesWithTrashedpivot()
	        			->select('sec_id')
	        			->where('sec_start_datetime', '<', $now->toDateTimeString())
	        			//->where('secc_if_make_up_created', 0)
	        			->where(function($query){
                            $query->where('secc_client_status', '!=', 'Waiting')
                                  ->orWhere('secc_if_make_up', 1);
                      	});
	    }*/

	    public function membChangeableClasses(){   
	        $now = new Carbon();
	        return $this->eventClassesWithTrashedpivot()
	       				->select('sec_id', 'sec_class_id', 'sec_date')
	        			->where('sec_start_datetime', '>=', $now->toDateTimeString())
	        			->where('secc_skip_memb_change', 0);
	    }

	    /*private function eventClassesList(){
	        return $this->eventClasses()
	                    ->select('sec_id', 'sec_class_id', 'sec_date', 'sec_last_neverend', 'sec_start_datetime', 'sec_date', 'sec_date as eventDate', 'sec_is_repeating', 'staff_event_classes.deleted_at', 'sec_staff_id', 'sec_class_id', 'sec_time', 'sec_time as eventTime');
	    }*/

	    public function membershipData($id){
	    	return \App\MemberShip::where('id', $id)->select('me_installment_amt','me_validity_type')->first()->toArray();
	    }
	/*
    **end: FUNCTIONS
    */
}