<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AbClientExerciseSet extends Model
{
    protected $table = 'ab_client_exercise_sets';
    
    protected $guarded = [];

    public function clientPlanWorkoutExercise(){
        return $this->belongsTo('App\AbClientExercises','ab_client_exercise_id');
    }
}
