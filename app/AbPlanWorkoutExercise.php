<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbPlanWorkoutExercise extends Model{
    const EXERCISE = 1, VIDEO = 2;
    use SoftDeletes;

    protected $table = 'ab_plan_workout_exercise';

    public function exercies(){
    	return $this->belongsTo('App\Exercise', 'exercise_id', 'id');
    }

    public function actvityVideo(){
        return $this->belongsTo('App\ActivityVideo', 'exercise_id', 'id');
    }

    public function exerciseSets(){
        return $this->hasMany('App\AbPlanWorkoutExerciseSet', 'ab_plan_workout_exercise_id');
    }

}
