<?php

namespace App\Result\Models;

use Eloquent as Model;

class WaistHipRatio extends Model
{
  	protected $table = 'waist_hip_ratio';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','gender','waist','hip','ratio','bs','interpretation'];

}
