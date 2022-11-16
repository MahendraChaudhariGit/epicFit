<?php

namespace App\Result\Models;

use Eloquent as Model;

class BasalMetabolismRate extends Model
{
	protected $table = 'basal_metabolism_rate';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','gender','equation_type','age','weight','height_in','height_ft','brm'];

}

