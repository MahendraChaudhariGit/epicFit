<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoalBuddyUpdate extends Model{
    use SoftDeletes;
    
    protected $table = 'goal_buddy_updates';
    protected $fillable = [
    'gb_client_id',
    'gb_user_id',
    'goal_id',
    'milestone_id',
    'habit_id',
    'task_id',
    'due_date',
    'status'
    ];

    public function goal(){
        return $this->hasOne('App\GoalBuddy', 'id', 'goal_id');//->orderBy('id','ASC');
    }

    public function milestone(){
        return $this->hasOne('App\GoalBuddyMilestones', 'id', 'milestone_id');//->orderBy('id','ASC');
    }

    public function habits(){
        return $this->hasOne('App\GoalBuddyHabit', 'id', 'habit_id');
    }

     public function task(){
        return $this->hasOne('App\GoalBuddyTask', 'id', 'task_id');
    }
    
    /*public function scopeOfUser($query){
        return $query->where( 'gb_user_id', Auth::id());
    }*/
   
}