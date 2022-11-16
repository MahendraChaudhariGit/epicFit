<?php
namespace App\Models\Access\User;

use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;
use DB;
use Illuminate\Support\Facades\Auth;
use App\UserType;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App\Models\Access\User
 */
class User extends Authenticatable{

    use SoftDeletes, UserAccess, UserAttribute, UserRelationship, Notifiable;

    //protected $fillable = ['account_id','name', 'last_name', 'email', 'password', 'telephone', 'referral', 'client_management', 'business_support', 'Knowledge', 'resources', 'mentoring', 'agree', 'confirmation_code'];
    protected $fillable = ['account_id','name', 'last_name', 'business_id', 'email', 'password', 'confirmation_code', 'ut_id', 'confirmed','telephone','profile_picture','address_line_one','address_line_two','city','country','state','postal_code','web_url','referral','client_management', 'business_support', 'Knowledge', 'resources', 'mentoring', 'agree','user_limit_id'];

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['deleted_at'];

    public function getFullNameAttribute(){
        //return $this->name.' '.$this->last_name;
        return $this->Fname.' '.$this->Lname;
    }

    public function getPkAttribute(){
        if($this->account_type == 'Admin')
            return $this->id;

        return $this->account_id;
    }

    public function getFnameAttribute(){
        if($this->account_type == 'Staff')
            return $this->origEntity->first_name;

        return $this->name;
    }

    public function getLnameAttribute(){
        if($this->account_type == 'Staff')
            return $this->origEntity->last_name;

        return $this->last_name;
    }

    public function getProfilePicAttribute(){
        if($this->account_type == 'Staff')
            return $this->origEntity->profile_picture;
        elseif($this->account_type == 'Client')
            return $this->origEntity->profilepic;
    }

    public function getGenderAttribute(){
        if($this->account_type == 'Staff')
            return $this->origEntity->gender;
    }

    public function origEntity(){
        if($this->account_type == 'Staff')
            return $this->belongsTo('App\Staff', 'account_id');  
        elseif($this->account_type == 'Client')
            return $this->belongsTo('App\Clients', 'account_id');      
    }

    public function businesses(){
        return $this->hasOne('App\Business');
    }

    public function businesParent(){
        return $this->belongsTo('App\Business', 'business_id');
    }

    public function eventClasses(){
        return $this->hasMany('App\StaffEventClass', 'sec_user_id');
    }

    public function eventAppointments(){
        //return $this->hasMany('App\StaffEvent', 'se_user_id');
        return $this->hasMany('App\StaffEventSingleService', 'sess_user_id');
    }

    public function tasks(){
        return $this->hasMany('App\Task', 'task_user_id');
    }

    public function taskCategory(){
        return $this->hasMany('App\TaskCategory', 't_cat_user_id');
    }

    public function busyTime(){
        return $this->hasMany('App\StaffEventBusy', 'seb_user_id');
    }
	
	public function services(){
        return $this->hasMany('App\Service', 'user_id');
    }
	
	 public function events(){
        return $this->hasMany('App\Event');
    }

    public function type(){
        return $this->belongsTo('App\UserType', 'ut_id');
    }

    public function perms(){
        return $this->belongsToMany('App\Permission', 'user_perms', 'up_usr_id', 'up_perm_id');
    }

    public function usersLimit(){
        return $this->belongsTo('App\UserLimit','user_limit_id');
    }

    /*static function getUserType($typeId = 0){
        if(!$typeId)
            return DB::table('user_types')->lists('ut_name', 'ut_id');
        else
            return array_first(DB::table('user_types')->where('ut_id', $typeId)->lists('ut_name'));
    }*/

    static function hasPermission($user, $permission){
        
        if(isSuperUser())
            return true;
        /*return true;
        $perms = UserType::find(1)->perms;
        foreach($perms as $perm){
            $prms[] = $perm->perm_name;
        }

        dd($prms );
        return true;
        $perms =  $prms = [];
	    if($user->is_custom_perm)
            $perms = $user->perms;//User::find($user->id)->perms;
        else{*/
            /* Remove following lines */
            //if($user->type->ut_name == 'Administrator')
               // return true;
            /* Remove above lines */
        $perms /*=  $prms*/ = [];
        $userRole = $user->type;
		if($userRole){
			$perms = $userRole->perms;
            foreach($perms as $perm)
                if($permission == $perm->perm_name)
                    return true;
        }
        
        return false;
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($user){
            if($user->forceDeleting && $user->account_type == 'Staff'){
                //DB::table('staff_events')->where('se_user_id', $user->id)->update(['se_user_id' => 0]);
                DB::table('staff_event_single_services')->where('sess_user_id', $user->id)->update(['sess_user_id' => 0]);
                DB::table('staff_event_classes')->where('sec_user_id', $user->id)->update(['sec_user_id' => 0]);
                DB::table('staff_event_busy')->where('seb_user_id', $user->id)->update(['seb_user_id' => 0]);
            }
        });
    }
}
