<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BusinessPlan extends Model{
    use SoftDeletes;
    protected $table = 'business_plans';
    protected $primaryKey = 'bp_id';
    //protected $fillable = [''];
  
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
    **end: EVENTS
    */
}
