<?php

namespace App\Result\Models;

use Eloquent as Model;

class BodyFatNavy extends Model
{
	protected $table = 'body_fat_navy';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','gender','waist','neck','hip','weight','height_ft','bf','fm','lm','bfc'];
}
