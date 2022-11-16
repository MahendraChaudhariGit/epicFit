<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffEventRepeat extends Model{
	use SoftDeletes;
	protected $table = 'staff_event_repeats';
	protected $primaryKey = 'ser_id';

	/*
    **start: SCOPES
    */
		public function scopeofTask($query){
            return $query->where('ser_event_type', 'App\Task');
        }

        public function scopeofClass($query){
            return $query->where('ser_event_type', 'App\StaffEventClass');
        }

        public function scopeofSingleService($query){
            return $query->where('ser_event_type', 'App\StaffEventSingleService');
        }
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
	    public function repeatable(){
	      return $this->morphTo();
	    }
	/*
    **end: RELATIONS
    */
}
