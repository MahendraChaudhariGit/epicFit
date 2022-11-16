<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClientPlanPhase extends Model
{
    protected $table = 'client_plan_phases';
    protected $guarded = [];

    public function planProgram(){
        return $this->belongsTo('App\AbClientPlanProgram','program_id');
    }
}
