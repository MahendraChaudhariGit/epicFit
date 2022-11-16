<?php

namespace App\Result\Models;

use Eloquent as Model;

class IdealWeight extends Model
{
	protected $table = 'ideal_weight';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','gender','height_ft','height_in','bmi','ideal_weight'];
}
