<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceItems extends Model{
    use SoftDeletes;
    protected $table = 'resources_items';
    protected $primaryKey = 'id';
    protected $fillable = ['resItem', 'resItemLoc'];
    //protected $fillable = ['resName'];

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
        public function location(){
            return $this->belongsTo('App\Location', 'ri_location');
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
    **end: EVENTS
    */
}
