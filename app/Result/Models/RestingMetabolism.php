<?php

namespace App\Result\Models;

use Eloquent as Model;

class RestingMetabolism extends Model
{
	protected $table = 'resting_metabolism';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','weight','lmi','lmi_type','rm','lm','lmp','fm','fmp'];
}
