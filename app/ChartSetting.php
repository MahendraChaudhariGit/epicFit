<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Session;
use App\Service;
use App\Clas;

use Illuminate\Database\Eloquent\Model;

class ChartSetting extends Model{
    use SoftDeletes;
    protected $table = 'chart_setting';
    protected $primaryKey = 'chart_id';
    //protected $fillable = ['resName', 'resItem', 'resItemLoc'];
    protected $fillable = ['chart_type'];
  
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
