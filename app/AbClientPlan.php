<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use DB;

class AbClientPlan extends Model{
    use SoftDeletes;

    protected $table = 'ab_client_plan';

    protected $guarded = [];

    public function program(){
        return $this->hasOne('App\AbPlans', 'id', 'fixedProgramId');
    }

    public function workouts(){
        return $this->belongsToMany('App\AbWorkout', 'ab_client_plan_workout', 'client_plan_id', 'workout_id')->withPivot('id','order')->where('ab_client_plan_workout.deleted_at',null);
    }

    public function plandates(){
        return $this->hasMany('App\AbClientPlanDate', 'client_plan_id', 'id');
    }

    public function activityVideo(){
        return $this->belongsTo('App\ActivityVideo','activity_video_id');
    }

    public function clientPhaseProgram(){
        return $this->hasMany('App\AbClientPlanProgram','client_plan_id');
    }

    public function clientPlanPhases(){
        return $this->hasMany('App\ClientPlanPhase','client_plan_id');
    }


    /** 
     * Boot 
    **/
    protected static function boot(){
        parent::boot();
        static::deleting(function($plan){
            // Delete plan date 
            DB::table('ab_clients_plan_dates')->where('client_plan_id', $plan->id)->delete();
        });
        /*static::deleted(function(){
            
        });*/
    }
    
}
