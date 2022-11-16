<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbClientPlanDate extends Model{
    use SoftDeletes;

    protected $table = 'ab_clients_plan_dates';
    protected $primaryKey = 'id';
    
}
