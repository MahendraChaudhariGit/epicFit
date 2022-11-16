<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model{
    use SoftDeletes;
    protected $table = 'movement';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $guarded = [];
  
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
