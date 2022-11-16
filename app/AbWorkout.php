<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbWorkout extends Model{
    use SoftDeletes;

    protected $table = 'ab_workout';

    public function plans(){
        return $this->belongsToMany('App\AbPlans', 'ab_plan_workout', 'workout_id', 'plan_id');
    }
    public function exercises(){
        return $this->belongsToMany('App\Exercise', 'ab_workout_exercise', 'workout_id','exercise_id');
    }
    
}
