<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbClientExercises extends Model{
    const EXERCISE = 1, VIDEO = 2;
    use SoftDeletes;

    protected $table = 'ab_client_exercises';
    protected $primaryKey = 'id';

    public function workout(){
        return $this->hasOne('App\AbWorkout', 'id' , 'workout_id');
    }
    public function exercise(){
        return $this->hasOne('App\Exercise', 'id', 'exercise_id');
    }

    public function actvityVideo(){
        return $this->belongsTo('App\ActivityVideo', 'exercise_id', 'id');
    }

    public function exerciseSets(){
        return $this->hasMany('App\AbClientExerciseSet', 'ab_client_exercise_id');
    }
    
}
