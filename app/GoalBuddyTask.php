<?php 
namespace App;
use Carbon\Carbon;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoalBuddyTask extends Model{
	use SoftDeletes;
	
	protected $fillable = [
	'goal_id',
	'gb_client_id',
	'gb_user_id',
	'is_primary',
	'gb_task_name',
	'gb_task_due_date',
	'gb_task_time',
	'gb_task_priority',
	'gb_habit_id',
	'gb_task_recurrence_type',
	'gb_task_recurrence_week',
	'gb_task_recurrence_month',
	'gb_task_seen',
	'gb_task_selective_friends',
	'gb_task_reminder',
	'gb_task_note',
	'gb_task_note_other',
	'gb_task_reminder_time',
	'is_step_completed',
	'gb_task_reminder_epichq'
	];
	public function getGbTaskDueDateAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }
    public function taskhabit(){
        // return $this->hasMany('App\GoalBuddyHabit', 'id', 'gb_habit_id');
        return $this->hasOne('App\GoalBuddyHabit', 'id', 'gb_habit_id');
    }

    public function getTaskHabitName($id){
		return $this->gb_habit_id ? GoalBuddyHabit::where('goal_id',$id)->pluck('gb_habit_name')->toArray():[];
	}
  /*  public function taskhabit(){
        return $this->belongsToMany('App\GoalBuddyHabit', 'task_habit', 'th_task_id','th_habit_id');
    }*/


}
