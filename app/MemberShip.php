<?php 
namespace App;
use DB;
use Session;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberShip extends Model{
	use SoftDeletes;
	
	protected $table = 'membership';
	protected $fillable = [
	'me_business_id',
	'me_membership_label',
	'me_validity_length',
	'me_validity_type',
	'me_class_limit',
	'me_class_limit_length',
	'me_class_limit_type',
	'me_auto_renewal',
	'me_auto_renewal_type',
	'me_installment_plan',
	'me_installment_amt',
	'me_prorate',
	'me_signup_fee',
	'me_change_signup_fee',
	'me_income_category',
	'me_enrollment_limit',
	'me_public',
	'me_public_description',
	'me_due_at_signup',
	'enrollment_start_date',
	'enrollment_end_date',
	'me_begin_date',
	'mem_begins_on_date',
	'mem_begins_on',
    'membership_totaltax',
    'addOn_member',
	'me_visible',
    'me_show_on_kiosk',
    'me_tax',
    'me_unit_amt'

	];

	public function setEnrollmentStartDateAttribute($date){
        $this->attributes['enrollment_start_date'] = $date?dateStringToDbDate($date):null;
    }

    public function getEnrollmentStartDateAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }
    public function setEnrollmentEndDateAttribute($date){
        $this->attributes['enrollment_end_date'] = $date?dateStringToDbDate($date):null;
    }

    public function getEnrollmentEndDateAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }
    public function setMeBeginDateAttribute($date){
        $this->attributes['me_begin_date'] = $date?dateStringToDbDate($date):null;
    }

    public function getMeBeginDateAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }
    public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->where('me_business_id', $bussId);
    }

    public function categorymember(){
        return $this->belongsToMany('App\MemberShipCategory', 'category_member', 'cme_member_id','cme_cat_id');
    }

    public function servicemember(){
        return $this->belongsToMany('App\Service', 'service_membership', 'sme_member_id','sme_service_id');
    }

    public function serviceMemberWithPivot(){
        return $this->servicemember()->withPivot('sme_service_limit','sme_service_limit_type');
    }

    public function classmember(){
        return $this->belongsToMany('App\Clas', 'class_membership', 'cm_member_id','cm_cl_id');
    }
    public function groupmember(){
        return $this->belongsToMany('App\MemberShipGroup', 'group_member', 'gme_member-id','gme_group_id');
    }

    public function staffmember(){
        return $this->belongsToMany('App\Staff', 'staff_member', 'stm_member_id','stm_staff_id');
    }
    
    public function membertax(){
        return $this->hasMany('App\MemberShipAddTax', 'mat_member_id');
    }

    public function taxes(){
        return $this->belongsToMany('App\MemberShipTax', 'membership_addtax', 'mat_member_id', 'mat_tax_id')->whereNull('membership_addtax.deleted_at');
    }

    static function calcTotalTax($membership){
        $totalTax = 0;
        if($membership->taxes->count()){
            foreach($membership->taxes as $tax){
                $totalTax += (int) $tax->mtax_rate;
            }
        }
        return $totalTax;
    }
}
