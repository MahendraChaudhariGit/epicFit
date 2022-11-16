<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AbPlanWorkoutExerciseSet extends Model
{
    protected $table = 'ab_plan_workout_exercise_sets';
    
    protected $guarded = [];

    public function clientPlanWorkoutExercise(){
        return $this->belongsTo('App\AbPlanWorkoutExercise','ab_plan_workout_exercise_id');
    }
}
