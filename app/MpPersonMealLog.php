<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class MpPersonMealLog extends Model
{

    protected $table = 'mp_person_meallog';
    protected $primaryKey = 'user_id,day_id';
    protected $fillable = ['breakfast','morning_snack','lunch','afternoon_snack','dinner','evening_snack'];
    
}