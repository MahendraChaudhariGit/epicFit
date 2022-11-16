<?php

namespace App\Result\Models;

use Eloquent as Model;

class DailyMetabolism extends Model
{
	protected $table = 'daily_metabolism';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','gender','activity','age','weight','height_ft','height_in','aam','aamph','arm'];
}
