<?php
namespace App\Http\Traits;
use Session;
use App\ServiceResources;
use DB;
/*use App\StaffEventClass;
use Carbon\Carbon;*/

trait ServiceResourceTrait{

	protected function storeResources($id,$request,$kase,$entitytype){
		$formData =$request->all();
        ksort($formData);
        $resources = $item = $newResources = $newItem = [];
		foreach($formData as $key => $value){
			/*if(strpos($key, 'resources') !== false)
                $resources[] = $value;
            else if(strpos($key, 'item') !== false)
				$item[] = $value;
			else*/ if(strpos($key, 'newResources') !== false)
				$newResources[] = $value;
			else if(strpos($key, 'newItem') !== false)
				$newItem[] = $value;  
		}
		//dd($entitytype);
		
		if($id && $kase=="edit"){
			/*
			if($request->category == 1 || $request->category == 2 ){
				ServiceResources::where('sr_entity_id',$id)->where('sr_entity_type',$entitytype)->forcedelete();
			}
			else{
				ServiceResources::where('sr_entity_id',$id)->where('sr_entity_type',$entitytype)->whereNull('deleted_at')->delete();
			}*/
			/*if($entitytype=='App\Clas'){
				$now = new Carbon();
				$futureClassbookings = StaffEventClass::with('resources')->OfBusiness()->where('sec_class_id', $id)->where('sec_start_datetime', '>=', $now->toDateTimeString())->orderBy('sec_date')->orderBy('sec_time')->get();
				dd($futureClassbookings);
			}*/
			//ServiceResources::where('sr_entity_id',$id)->where('sr_entity_type',$entitytype)->forcedelete();
			ServiceResources::where('sr_entity_id',$id)->where('sr_entity_type',$entitytype)->whereIn('sr_res_id', $newResources)->forcedelete();
			ServiceResources::where('sr_entity_id',$id)->where('sr_entity_type',$entitytype)->delete();
		}

		// Update case
		/*if(count($resources) && count($item) ){
			for($i=0;$i<count($item);$i++){
				ServiceResources::where('id',$itemId[$i])->where('sr_business_id',Session::get('businessId'))->update(['sr_res_id'=>$resources[$i] ,'sr_item_quantity'=>$item[$i]]);
            }
		}*/

		// Insert case
		if( ( $entitytype=='App\Clas') || ($request->category == 1 || $request->category == 2 )){
			if(count($newResources) && count($newItem)){
				$newresArray =[];
				for($i=0;$i<count($newItem);$i++){
					$timestamp = createTimestamp();
	                $newresArray[] = ['sr_business_id'=>Session::get('businessId'),'sr_entity_type'=>$entitytype,'sr_entity_id'=>$id,'sr_res_id'=>$newResources[$i],'sr_item_quantity'=>$newItem[$i],'created_at'=>$timestamp,'updated_at'=>$timestamp];  
	            }
				if(count($newresArray))
					ServiceResources::insert($newresArray);
			}
		}
		

	}
}