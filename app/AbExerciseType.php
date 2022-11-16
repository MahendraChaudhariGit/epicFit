<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbExerciseType extends Model{
    use SoftDeletes;

    protected $table = 'ab_exercise_type';
   	protected $primaryKey = 'id';
    protected $fillable = ['type_name'];

    // public function exercises(){
    //     return $this->belongsToMany('App\Exercise', 'ab_exercise_resources', 'resource_id', 'exercise_id');
    // }
    
}
