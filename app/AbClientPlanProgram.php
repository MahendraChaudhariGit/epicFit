<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbClientPlanProgram extends Model
{
    use SoftDeletes;
    protected $table = 'ab_client_plan_programs';

    protected $guarded = [];

    public function workouts(){
        return $this->belongsToMany('App\AbWorkout', 'plan_multi_phase_workouts', 'plan_program_id', 'workout_id')->withPivot('id','order_no')->where('plan_multi_phase_workouts.deleted_at',null);
    }
}
