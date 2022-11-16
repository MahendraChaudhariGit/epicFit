<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbWorkoutExercise extends Model{
    use SoftDeletes;

    protected $table = 'ab_workout_exercise';

    
    public function exercises(){
        return $this->belongsTo('App\Exercise', 'exercise_id', 'id');
    }
    /*public function exercises(){
        return $this->hasOne('App\Exercise', 'exerciseTypeID', 'exercise_id');
    }*/
    
}
