<?php 
namespace App;
use Carbon\Carbon;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoalBuddyMilestones extends Model{
	use SoftDeletes;
	protected $table = 'goal_buddy_milestones';
	protected $primaryKey = 'id';
	
	protected $fillable = [
	'goal_id',
	'gb_user_id',
	'gb_milestones_name',
	'gb_milestones_date',
	'gb_milestones_status',
	'gb_milestones_seen',
	'gb_milestones_selective_friends',
	'gb_milestones_reminder',
	'gb_milestones_reminder_epichq',
	'gb_client_id',
	'gb_milestones_reminder_time',
	'is_step_completed'
	];

	static function updateMilestones($input, $id){
		return DB::table('goal_buddy_milestones ')->where('id', $id)->update($input);
	}

	 public function getGbMilestonesDateAttribute($value){
        if($value && $value != '0000-00-00')
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }

}
