<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ServiceType extends Model{
    use SoftDeletes;
	protected $table = 'service_types';
	protected $primaryKey = 'st_id';


    /*
    **start: ACCESSOR
    */
    /*
    **end: ACCESSOR
    */


    /*
    **start: SCOPES
    */
        public function scopeOfBusiness($query){
            return $query->where('st_business_id', Session::get('businessId'));
        }
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
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
