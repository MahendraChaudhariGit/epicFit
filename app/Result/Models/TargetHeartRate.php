<?php

namespace App\Result\Models;

use Eloquent as Model;

class TargetHeartRate extends Model
{
	protected $table = 'target_heart_rate';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','goal','age','rhra','bpml','bpmh','mhr','bptsl','bptsh','mhrits'];
}
