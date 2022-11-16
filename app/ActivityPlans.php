<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ActivityPlans extends Model{
    use SoftDeletes;

    protected $table = 'ab_plans';
    
}
