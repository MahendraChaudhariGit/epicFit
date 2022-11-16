<?php

namespace App\Result\Models;

use Eloquent as Model;

class AdvancedRestingMetabolism extends Model
{
	protected $table = 'advanced_resting_metabolism';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','gender','age','weight','height_ft','height_in','rm'];
}
