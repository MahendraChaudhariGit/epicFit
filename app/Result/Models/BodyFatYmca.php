<?php

namespace App\Result\Models;

use Eloquent as Model;

class BodyFatYmca extends Model
{
	protected $table = 'body_fat_ymca';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','type','gender','weight','waist','bf','fm','lm','bfc'];
}
