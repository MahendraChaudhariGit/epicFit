<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbExerciseVideo extends Model{
    use SoftDeletes;

    protected $table = 'ab_exercise_video';
    protected $primaryKey = 'aei_id';
    protected $guarded = [];

	public function exercise(){
        return $this->belongsTo('App\Exercise','aei_exercise_id','id');
    }	 
}
