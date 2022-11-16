<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MovementStepSetup extends Model{
    use SoftDeletes;
    protected $table = 'movement_step_setup';
    protected $primaryKey = 'mss_id';
    protected $dates = ['deleted_at'];
    protected $fillable = ['mss_business_id','mss_client_id','mss_step_name'];
  
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
    **start: EVENTS
    */
        
    
    /*
    **end: EVENTS
    */
}
