<?php

namespace App\Result\Models;

use Eloquent as Model;

class FullBodyAnalysis extends Model
{
	protected $table = 'full_body_analysis';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','age','rhra','waist','hip','elbow','activity','goal','weight','height_ft','height_in','bmi','classification','weight_range','ratio','bs','interpretation','ideal_weight','lm','lmp','fm','fmp','aam','aamph','arm','bpml','bpmh','mhr','bptsl','bptsh','mhrits'];
}
