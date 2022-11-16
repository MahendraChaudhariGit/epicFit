<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffEventClassRepeat extends Model{
	use SoftDeletes;

	protected $table = 'staff_event_class_repeats';
    protected $primaryKey = 'secr_id';
    protected $fillable = ['secr_repeat', 'secr_repeat_interval', 'secr_repeat_end', 'secr_repeat_end_after_occur', 'secr_repeat_end_on_date', 'secr_repeat_week_days', 'secr_child_count', 'secr_area_id','secr_client_id','secr_staff_id','secr_start_time','sece_end_time','secr_class_id','secr_duration','secr_capacity','secr_price','secr_date','secr_resources'];

    public function childEvents(){
        return $this->hasMany('App\StaffEventClass', 'sec_secr_id', 'secr_id');
    }

    public function siblingEvents(){
        return $this->hasMany('App\StaffEventClass', 'sec_secr_id', 'secr_id');
    }
}
