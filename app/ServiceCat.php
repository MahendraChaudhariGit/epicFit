<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ServiceCat extends Model{
    use SoftDeletes;
	protected $table = 'service_cats';
	protected $primaryKey = 'sc_id';


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
            return $query->where('sc_business_id', Session::get('businessId'));
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
