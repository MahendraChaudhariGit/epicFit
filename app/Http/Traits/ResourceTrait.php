<?php
namespace App\Http\Traits;
use App\Resource;
use Session;

trait ResourceTrait{
	protected function resourceData($locationId, $ifAjax=true){
		$res = Resource::select('res_name','id')
						->whereHas('items', function($query) use($locationId){
							$query->where('ri_location',$locationId);
						})
						->with(array('items' => function($query) use($locationId){
							$query->where('ri_location', $locationId);
						}))
						->where('res_business_id',Session::get('businessId'))
						->get();

        if($ifAjax)
            return json_encode($res);
        else
            return $res;
    }
}