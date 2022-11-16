<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PlanMultiPhaseWorkout extends Model
{
    use SoftDeletes;
    protected $table = 'plan_multi_phase_workouts';

    protected $guarded = [];

    public function workout(){
        return $this->belongsTo('App\AbWorkout','workout_id');
    }
}
