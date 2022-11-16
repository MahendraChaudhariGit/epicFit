<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffEventSkip extends Model{
    use SoftDeletes;
	protected $table = 'staff_event_skips';
	protected $primaryKey = 'sk_id';

    /*
    **start: ACCESSOR
    */
    /*
    **end: ACCESSOR
    */


    /*
    **start: SCOPES
    */
        public function scopeofClass($query){
            return $query->where('sk_event_type', 'App\StaffEventClass');
        }

        public function scopeofSingleService($query){
            return $query->where('sk_event_type', 'App\StaffEventSingleService');
        }

        public function scopeofTask($query){
            return $query->where('sk_event_type', 'App\Task');
        }
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
        public function skipable(){
          return $this->morphTo();
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
