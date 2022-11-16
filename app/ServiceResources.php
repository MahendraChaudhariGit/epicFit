<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ServiceResources extends Model{
	use SoftDeletes;
	protected $table = 'service_resources';
	protected $primaryKey = 'sr_id';

	/*
    **start: SCOPES
    */
		public function scopeOfClas($query, $clasId){
            return $query->OfBusiness()->where('sr_entity_id',$clasId)->where('sr_entity_type','App\Clas');
        }

        public function scopeOfService($query, $servId){
            return $query->OfBusiness()->where('sr_entity_id',$servId)->where('sr_entity_type','App\Service');
        }

        public function scopeOfBusiness($query){
            return $query->where('sr_business_id', Session::get('businessId'));
        }

        public function scopeWithResource($query, $locId){
            return $query->with(array('resourceWithTrashed' => function($query){
                                $query->select('id', 'res_name', 'deleted_at');
                            }, 'resourceWithTrashed.itemsWithTrashed' => function($query) use($locId){
                                $query->where('ri_location', $locId)->select('ri_id');
                            })
                         )
                         ->select('sr_res_id', 'sr_item_quantity', 'deleted_at');
        }
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
	    public function resorcesable(){
	      return $this->morphTo();
	    }

        public function resource(){
            return $this->belongsTo('App\Resource', 'sr_res_id');
        }

        public function resourceWithTrashed(){
            return $this->resource()->withTrashed();
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
}