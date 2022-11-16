<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Illuminate\Database\Eloquent\Model;
use Session;

class CalendarSetting extends Model{
    use SoftDeletes;
    protected $table = 'calendar_settings';
    protected $primaryKey = 'id';
    //protected $fillable = ['resName', 'resItem', 'resItemLoc'];
    protected $fillable = ['cs_first_day','cs_start_time','cs_intervals','cs_initial_status','cs_initial_status_consultation','cs_initial_status_benchmarking','sales_process_settings'];
  
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
      public function reason(){
        return $this->hasMany('App\CalendarSettingsCancelReasons','cscr_id');
      }
    /*
    **end: RELATIONS
    */


    /*
    **start: FUNCTIONS
    */
        /*static function getIt(){
            return CalendarSetting::where('cs_business_id',Session::get('businessId'))->where('cs_client_id',0)->first()->toArray();
        }*/
    /*
    **end: FUNCTIONS
    */


    /*
    **start: EVENTS
    */
     /*protected static function boot(){
        parent::boot();
        static::deleting(function($res){
            DB::table('resources_items')->where('ri_id', $res->id)->update(array('deleted_at' => createTimestamp()));
             
        });
      }

    /*
    **end: EVENTS
    */
}
