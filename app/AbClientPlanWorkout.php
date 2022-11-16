<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbClientPlanWorkout extends Model{
    use SoftDeletes;

    protected $table = 'ab_client_plan_workout';
    public function workout(){
        return $this->belongsTo('App\AbWorkout','workout_id');
    }
}
