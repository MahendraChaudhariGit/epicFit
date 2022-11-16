<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ClientNote extends Model{
    use SoftDeletes;
    
	protected $table = 'client_notes';
	protected $primaryKey = 'cn_id';
    protected $dates = ['created_at'];
    protected $fillable = ['cn_client_id','cn_user_id','cn_type','cn_notes','cn_extra','cn_source'];
    /*
    **start: ACCESSOR
    */
    /*
    **end: ACCESSOR
    */


    /*
    **start: SCOPES
    */
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
      public function makeup()
      {
        return $this->hasOne('App\Makeup','makeup_notes_id','cn_id');
      }

      public function category(){
        return $this->hasOne('App\NotesCategory','nc_slug','cn_type');
      }
    /*
    **end: RELATIONS
    */


    /*
    **start: FUNCTIONS
    */
    /*
    **end: FUNCTIONS
    */


    /*
    **start: EVENTS
    */
    /*
    **END: EVENTS
    */
}
