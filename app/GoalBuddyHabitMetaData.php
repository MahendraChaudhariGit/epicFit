<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoalBuddyHabitMetaData extends Model{
	use SoftDeletes;
	protected $fillable = [
    'habit_id',
    'date',
    'week_day',
    'day_name',
    'month_day'
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
        return DB::table('goal_buddy_habits')->where('goal_id', $goal_id)->select('gb_habit_name','gb_milestones_name','id', 'gb_habit_recurrence','gb_habit_recurrence_month','gb_habit_recurrence_week', 'gb_habit_seen','created_at')->get();
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
        return DB::table('goal_buddy_habits')->where('id', $id)->select('gb_habit_name','gb_milestones_name','goal_id','id', 'gb_habit_recurrence','gb_habit_notes','gb_habit_recurrence_month','gb_habit_recurrence_week', 'gb_habit_seen','gb_habit_recurrence_type')->get();
    }
    static function getTaskById($id){
        return DB::table('goal_buddy_tasks')->where('id', $id)->select('gb_task_name','gb_task_due_date','goal_id','id', 'gb_task_time','gb_task_priority','gb_task_seen','gb_task_reminder', 'gb_milestones_name')->get();
    }
    static function getGoalByUser($uid){
    	return DB::table('goal_buddies')->where('gb_user_id', $uid)->where('deleted_at',NULL)->select('gb_goal_name','gb_due_date','id','gb_goal_seen','gb_goal_status')->get();
    }
}
