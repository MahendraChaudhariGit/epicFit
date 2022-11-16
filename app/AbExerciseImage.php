<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbExerciseImage extends Model{
    use SoftDeletes;

    protected $table = 'ab_exercise_image';
    protected $primaryKey = 'aei_id';
    protected $fillable = ['aei_image_name'];

	public function exercise(){
        return $this->belongsTo('App\Exercise','aei_exercise_id','id');
    }	 
}
