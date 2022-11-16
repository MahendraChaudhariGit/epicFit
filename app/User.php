<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, SoftDeletes;
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
	protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','pk'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function getProfilePicAttribute(){

  //dd($this->origEntity);
        return $this->origEntity->profilepic;
   }

   public function getGenderAttribute(){
    
      return $this->origEntity->gender;
     }


    public function getFullNameAttribute(){
    return $this->name.' '.$this->last_name;
    } 
    
    public function origEntity(){
   
    return $this->belongsTo('App\Clients', 'account_id'); 
    }



    public function eventAppointments(){
        //return $this->hasMany('App\StaffEvent', 'se_user_id');
        return $this->hasMany('App\StaffEventSingleService', 'sess_user_id');
    }

    public function eventClasses(){
        return $this->hasMany('App\StaffEventClass', 'sec_user_id');
    }

    
}