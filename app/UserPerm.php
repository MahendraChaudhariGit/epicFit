<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserPerm extends Model{

    protected $table = 'user_perms';
    protected $primaryKey = 'ut_id';
	//protected $fillable = ['up_ut_id', 'up_perm_id'];
	//protected $dates = ['up_created_at','up_updated_at'];

	static function deletePerm($userId,$permId){
	  return(DB::table('user_perms')->where('up_ut_id', $userId)->where('up_perm_id', $permId)->delete());
	}

	static function insertPerm($data){
	  return(DB::table('user_perms')->insert($data));
	}

}
