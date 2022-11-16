<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffEventResource extends Model{
	use SoftDeletes;
	protected $table = 'staff_event_resources';
	protected $primaryKey = 'serc_id';

	/*
    **start: SCOPES
    */
        public function scopeOfEventclas($query, $eventId){
            return $query->where('serc_event_id',$eventId)->where('serc_event_type','App\StaffEventClass');
        }

        public function scopeOfEventservice($query, $eventId){
            return $query->where('serc_event_id',$eventId)->where('serc_event_type','App\StaffEventSingleService');
        }
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
        public function resourceble(){
          return $this->morphTo();
        }

        /*public function resource(){
            return $this->belongsTo('App\Resource', 'serc_res_id');
        }

        public function resourceWithTrashed(){
            return $this->resource()->withTrashed();
        }*/
	/*
    **end: RELATIONS
    */
}