<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PlanMultiPhaseWorkoutExercise extends Model
{
    use SoftDeletes;
    protected $table = 'plan_multi_phase_workout_exercises';

    protected $guarded = [];

    public function exercies(){
    	return $this->belongsTo('App\Exercise', 'exercise_id', 'id');
    }

    public function actvityVideo(){
        return $this->belongsTo('App\ActivityVideo', 'exercise_id', 'id');
    }

    public function exerciseSets(){
        return $this->hasMany('App\PlanMultiPhaseWorkoutExerciseSet', 'plan_multi_phase_workout_exercise_id');
    }
}
