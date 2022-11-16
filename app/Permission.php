<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Permission extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions_new';
    protected $primaryKey = 'perm_id';

    public function userOwner(){
        return $this->belongsToMany('App\Models\Access\User\User');
    }

    public function userTypeOwner(){
        return $this->belongsToMany('App\UserType');
    }
 	static function getPermissionDetails($userId){
	   $permissionValues = DB::table('user_perms as uperm')->select()->join('permissions_new as pn','uperm.up_perm_id','=','pn.perm_id')->where('uperm.up_ut_id',$userId)->get(array('up_id','up_ut_id','up_perm_id','perm_name','perm_display_name'));

	   return $permissionValues;
	}
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    //protected $fillable = ['trading_name', 'type', 'relationship', 'cp_first_name', 'cp_last_name', 'description', 'currency', 'time_zone', 'logo', 'website', 'facebook', 'email', 'phone', 'address_line_one', 'address_line_two', 'city', 'country', 'state', 'postal_code', 'venue_location', 'billing_info'];
}
