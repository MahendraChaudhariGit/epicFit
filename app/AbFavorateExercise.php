<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbFavorateExercise extends Model{
    use SoftDeletes;

    protected $table = 'ab_favourite_exercise';

    // public function exercises(){
    //     return $this->belongsToMany('App\Exercise', 'ab_exercise_resources', 'resource_id', 'exercise_id');
    // }
    
}
