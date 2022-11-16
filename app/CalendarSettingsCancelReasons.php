<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

use Illuminate\Database\Eloquent\Model;

class CalendarSettingsCancelReasons extends Model{
    use SoftDeletes;
    protected $table = 'calendar_settings_cancel_reasons';
    protected $primaryKey = 'id';
    protected $fillable = ['cscr_reason'];
  
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
      /*public function items(){
       return $this->hasMany('App\ResourceItems','ri_id');
       }*/
       public function calSetting(){
        return $this->belongsTo('App\CalendarSetting','cscr_id');
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
      /* protected static function boot(){
        parent::boot();
        static::deleting(function($res){
            DB::table('resources_items')->where('ri_id', $res->id)->update(array('deleted_at' => createTimestamp()));
             
        });
      }*/

    /*
    **end: EVENTS
    */
}
