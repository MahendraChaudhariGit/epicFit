<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbExerciseMovementPattern extends Model{
    use SoftDeletes;

    protected $table = 'ab_exercise_movement pattern';
    protected $primaryKey = 'id';
    protected $fillable = ['pattern_name'];
    // public function exercises(){
    //     return $this->belongsToMany('App\Exercise', 'ab_exercise_resources', 'resource_id', 'exercise_id');
    // }
    
}
