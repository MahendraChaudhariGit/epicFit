<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class MpMealFood extends Model
{

    protected $table = 'mp_mealfood';
    
    protected $primaryKey = 'mealfood_id';

    protected $guarded = [];




    public function foodId()
	  {
	    return $this->hasOne('App\MpFoods','food_id');
	  }
    
}
	 	 	 	 	 	 