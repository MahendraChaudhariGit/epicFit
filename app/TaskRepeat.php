<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskRepeat extends Model{
	use SoftDeletes;

	protected $table = 'task_repeats';
    protected $primaryKey = 'tr_id';
    protected $fillable = 	['tr_business_id','tr_repeat','tr_repeat_interval','tr_repeat_end','tr_repeat_end_after_occur','tr_repeat_end_on_date','tr_repeat_week_days','tr_child_count','tr_task_user','tr_task_name','tr_task_category','tr_due_time','tr_task_type'];

    public function childEvents(){
        return $this->hasMany('App\Task', 'task_tr_id','tr_id');
    }
}
