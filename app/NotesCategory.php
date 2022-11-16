<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
//use DB;
use Illuminate\Database\Eloquent\Model;
use Session;

class NotesCategory extends Model{
    use SoftDeletes;
    protected $table = 'notes_category';
    protected $primaryKey = 'nc_id';
    protected $fillable = ['nc_business_id','nc_slug','nc_name'];
  
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
            return $query->where('nc_business_id', Session::get('businessId'));
        }
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
      public function notes(){
        return $this->hasMany('App\ClientNote','cn_type','nc_slug');
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
     
    /**end: EVENTS
    */
}
