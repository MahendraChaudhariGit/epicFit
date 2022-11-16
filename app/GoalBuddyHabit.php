<?php 
namespace App;
use DB;
use App\GoalBuddyTask;
use App\GoalBuddyUpdate;
use App\GoalBuddyMilestones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoalBuddyHabit extends Model{
	use SoftDeletes;
	
	protected $fillable = [
	    'goal_id',
        'gb_client_id',
		'gb_user_id',
		'is_primary',
		'gb_habit_name',
		'gb_habit_recurrence',
		'gb_habit_notes',
		'gb_milestones_id',
		'gb_habit_seen',
        'gb_habit_selective_friends',
		'gb_habit_recurrence_week',
		'gb_habit_recurrence_month',
		'gb_habit_reminder',
		'gb_habit_recurrence_type',
        'gb_habit_reminder_time',
        'is_step_completed',
        'gb_habit_note_other',
        'gb_habit_reminder_epichq'
	];

    public function getGbHabitWeekDetailsAttribute($value){
        return explode(',', $this->gb_habit_recurrence_week);
    }

    public function milestones(){
        return $this->hasOne('App\GoalBuddyMilestones', 'id', 'gb_milestones_name');
    }
    public function habitmilestone(){
        return $this->belongsToMany('App\GoalBuddyMilestones', 'habit_milestone', 'hm_habit_id','hm_milestone_id');
    }

    public function getMilestoneNames(){
		return $this->gb_milestones_id ? GoalBuddyMilestones::whereIn('id', explode(',', $this->gb_milestones_id))->pluck('gb_milestones_name')->toArray() : [];
	}

	public function habittask(){
        return $this->belongsTo('App\GoalBuddyTask');
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($habit){
            /* Deleting linked task */
            GoalBuddyTask::where('gb_habit_id', $habit->id)->delete();

            /*Deleting linked calender*/
            GoalBuddyUpdate::where('habit_id', $habit->id)->delete();
        });
    }
  
   
}
