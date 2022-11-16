<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class UserType extends Model{
	use SoftDeletes;
    protected $table = 'user_types';
    protected $primaryKey = 'ut_id';

    public function users(){
        return $this->hasMany('App\Models\Access\User\User', 'ut_id');
    }

    public function perms(){
        return $this->belongsToMany('App\Permission', 'user_perms', 'up_ut_id', 'up_perm_id');
    }
	static function groupExists($groupName){
		return DB::table('user_types')->where('ut_name', $groupName)->value('ut_name');;
    }


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    //protected $fillable = ['trading_name', 'type', 'relationship', 'cp_first_name', 'cp_last_name', 'description', 'currency', 'time_zone', 'logo', 'website', 'facebook', 'email', 'phone', 'address_line_one', 'address_line_two', 'city', 'country', 'state', 'postal_code', 'venue_location', 'billing_info'];

    
    /*public function users(){
        return $this->hasMany('App\Models\Access\User', 'up_usr_id', 'perm_id');
    }*/
}
