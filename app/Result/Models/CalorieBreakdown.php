<?php

namespace App\Result\Models;

use Eloquent as Model;

class CalorieBreakdown extends Model
{
	protected $table = 'calorie_breakdown';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id','gender','age','calorie','fatl','fath','proteinl','proteinh','fiber','sugar','carbohydratel','carbohydrateh'];
}
