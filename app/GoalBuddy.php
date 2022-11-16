<?php 
namespace App;
use Carbon\Carbon;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoalBuddy extends Model{
	use SoftDeletes;
	protected $fillable = [
    'gb_client_id',
    'gb_user_id',
    'gb_user_name',
    'gb_user_pic',
    'gb_company_name',
    'gb_goal_name',
    'gb_goal_name_other',
    'gb_achieve_description',
    'gb_achieve_description_other',
    'gb_is_top_goal',
    'gb_change_life_reason',
    'gb_change_life_reason_other',
    'gb_important_accomplish',
    'gb_important_accomplish_other',
    'gb_relevant_goal',
    'gb_relevant_goal_other',
    'gb_fail_description',
    'gb_fail_description_other',
    'gb_image_url',
    'gb_template',
    'gb_goal_seen',
    'gb_goal_selective_friends',
    'gb_due_date',
    'gb_reminder_type',
    'gb_habit_name',
    'gb_habit_recurrence',
    'gb_habit_recurrence_week',
    'gb_habit_recurrence_month',
    'gb_habit_notes',
    'gb_habit_seen',
    'gb_habit_reminder',
    'gb_task_name',
    'gb_task_due_date',
    'gb_goal_review',
    'gb_task_priority',
    'gb_task_reminder',
    'gb_task_seen',
    'gb_relevant_goal_event',
    'gb_relevant_goal_event_other',
    'gb_goal_status',
    'gb_goal_notes',
    'gb_reminder_goal_time',
    'final_submitted',
    'is_step_completed',
    'final_submitted',
    'gb_reminder_type_epichq',
    'gb_start_date'
    ];

	static function updateBuddy($input, $id){
		return DB::table('goal_buddies')->where('id', $id)->update($input);
	}

	static function getClientDetails($business_id){
        return DB::table('clients')->join('businesses', 'clients.business_id', '=', 'businesses.id')->select('clients.firstname','clients.lastname','clients.id','clients.gender','clients.profilepic','businesses.trading_name')->get();
    }

    static function getSearchClient($condition){
       return DB::select('select clients.firstname,clients.lastname,clients.id,clients.gender,clients.profilepic,businesses.trading_name from clients inner join parqs on clients.id = parqs.client_id inner join businesses on clients.business_id = businesses.id  where  '.$condition.'');
	}

	static function updateHabit($input, $id){
		return DB::table('goal_buddy_habits')->where('id', $id)->update($input);
	}
	static function updateTask($input, $id){
		return DB::table('goal_buddy_tasks')->where('id', $id)->update($input);
	}
	static function getHabit($goal_id){
        return DB::table('goal_buddy_habits')->where('goal_id', $goal_id)->select('gb_habit_name','gb_milestones_name','id', 'gb_habit_recurrence','gb_habit_recurrence_month','gb_habit_recurrence_week', 'gb_habit_recurrence_type','gb_habit_seen','created_at')->get();
    }
    static function getTask($goal_id){
        return DB::table('goal_buddy_tasks')->where('goal_id', $goal_id)->select('gb_task_name', 'gb_milestones_name','id','gb_task_due_date', 'gb_task_priority','gb_task_seen')->get();
    }
    static function getMilestone($goal_id){
        return DB::table('goal_buddy_milestones')->where('goal_id', $goal_id)->select('gb_milestones_name')->get();
    }
    static function getSaveData($goal_id){
        return DB::table('goal_buddies')->where('id', $goal_id)->select('gb_goal_name','gb_achieve_description','gb_change_life_reason','gb_due_date','gb_fail_description','gb_goal_seen')->get();
    }
	static function getHabitById($id){
        return DB::table('goal_buddy_habits')->where('id', $id)->select('gb_habit_name','gb_milestones_id','goal_id','id', 'gb_habit_recurrence','gb_habit_notes','gb_habit_recurrence_month','gb_habit_recurrence_week', 'gb_habit_seen','created_at','gb_habit_recurrence_type')->get();
    }
    static function getTaskById($id){
        return DB::table('goal_buddy_tasks')->where('id', $id)->get();
    }
    static function getGoalByUser($uid){
    	return DB::table('goal_buddies')->where('gb_user_id', $uid)->where('deleted_at',NULL)->select('gb_goal_name','gb_due_date','id','gb_goal_seen','gb_goal_status')->get();
    }
    

    public function goalBuddyMilestones(){ 
        // return $this->hasMany('App\GoalBuddyMilestones','goal_id');
        // return $this->hasMany('App\GoalBuddyMilestones','goal_id')->orderBy('created_at', 'ASC');
        return $this->hasMany('App\GoalBuddyMilestones','goal_id')->orderBy('gb_milestones_date', 'ASC');
    }
    public function goalBuddyHabit(){
        // return $this->hasMany('App\GoalBuddyHabit','goal_id')->orderBy('created_at','DESC');
        return $this->hasMany('App\GoalBuddyHabit','goal_id')->orderBy('created_at','ASC');
    }

    public function goalBuddyTask(){
        // return $this->hasMany('App\GoalBuddyTask','goal_id');
        //return $this->hasMany('App\GoalBuddyTask','goal_id')->orderByRaw("FIELD(gb_task_priority , 'Low', 'Normal', 'High', 'Urgent') DESC");
        return $this->hasMany('App\GoalBuddyTask','goal_id')->orderBy('created_at', 'ASC');


        
    }

    public function goalBuddyTaskc1(){
        return $this->hasMany('App\GoalBuddyTask','goal_id')->orderBy('gb_habit_id', 'ASC');
    }

    public function goalBuddyTaskId(){
        return $this->hasMany('App\GoalBuddyTask','goal_id')->orderBy('id', 'ASC');
    }

     public function getGoalDueDateAttribute(){
        if($this->gb_due_date != '0000-00-00')
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $this->gb_due_date));
        return '';
    }
     public function getGbChangeLifeReasonDetailsAttribute($value){
        return explode(',', $this->gb_change_life_reason);
    }
}
