<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffEventSingleServiceRepeat extends Model{
	use SoftDeletes;

	protected $table = 'staff_event_single_services_repeats';
    protected $primaryKey = 'sessr_id';
    protected $fillable = 	['sessr_id','sessr_business_id','sessr_repeat','sessr_repeat_interval','sessr_repeat_end','sessr_repeat_end_after_occur','sessr_repeat_end_on_date','sessr_repeat_week_days','sessr_child_count','sessr_area_id','sessr_client_id','sessr_staff_id','sessr_start_time','sece_end_time','sessr_service_id','sessr_duration','sessr_price','sessr_with_invoice','sessr_booking_status','sessr_auto_expire','sessr_resources'];

    public function childEvents(){
        return $this->hasMany('App\StaffEventSingleService', 'sess_sessr_id','sessr_id');
    }

    public function siblingEvents(){
        return $this->hasMany('App\StaffEventSingleService', 'sess_sessr_id', 'sessr_id');
    }
}
