<?php

namespace App\Result\Models;

use Eloquent as Model;

class LeanBodyMass extends Model
{
	protected $table = 'lean_body_mass';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','gender','weight','height_ft','height_in','bf','fm','lm','bfc'];
}
