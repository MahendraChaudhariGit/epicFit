<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbPlans extends Model{
    use SoftDeletes;

    protected $table = 'ab_plans';

    public function workouts(){
        return $this->belongsToMany('App\AbWorkout', 'ab_plan_workout', 'plan_id', 'workout_id')->withPivot('id');
    }
    public function exercises(){
        return $this->belongsToMany('App\AbWorkout', 'ab_plan_workout', 'plan_id', 'workout_id')->withPivot('id');
    }
    
}
