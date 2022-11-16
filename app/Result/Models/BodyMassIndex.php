<?php

namespace App\Result\Models;

use Eloquent as Model;

class BodyMassIndex extends Model
{
	protected $table = 'body_mass_index';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','weight','height_in','height_ft','bmi','clasification','weight_renge'];
}
