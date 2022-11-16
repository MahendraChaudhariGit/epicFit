<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesProcess extends Model{
	use SoftDeletes;
    protected $table = "sales_processes";
    protected $dates = ['created_at'/*, 'sp_comp_date'*/];

    /*
    **start: ACCESSOR
    */
        public function getCompletedOnAttribute(){
            /*return $this->sp_comp_date->format('D, j M Y');*/
            /*return $this->created_at->format('D, j M Y'); */
            return setLocalToBusinessTimeZone($this->created_at,'dateString');
        }
    /*
    **end: ACCESSOR
    */


    /*
    **start: SCOPES
    */
    	public function scopeOfType($query, $type){
            return $query->whereIn('sp_type', $type);
        }

        public function scopeOfClientAndOfType($query, $clientId, $type){
            return $query->where('sp_client_id', $clientId)->OfType($type);
        }
    /*
    **end: SCOPES
    */
}
