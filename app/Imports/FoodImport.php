<?php

namespace App\Imports;

use App\MpServingSize;
use App\MpFoods;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class FoodImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	// $rows = $collection->toArray();
        if($collection->count()){
            $foodDatas = array();
            foreach ($collection as  $row) {
                foreach ($row as $key => $col) {
                    if($col->name != ''){
                        $foodDatas[] = $col->toArray();  
                    }
                }
            }

            if(count($foodDatas)){
                $isError = false;
                $attributes = \Schema::getColumnListing('mpn_food');
                foreach ($foodDatas[0] as $key => $value) {
                    if($key != 'serving' && $key != 0 && !in_array($key, $attributes)){
                        $isError = true;
                        break;
                    }
                }
                if(!$isError){
                    $foodId = '';
                    foreach ($foodDatas as $fooddata){
                        $servingSize = explode('=', $fooddata['serving']);
                        if(count($servingSize) <= 1){ // save food
                            $foodInserted = array('business_id'=>$businessId,'created_at'=>$timeStamp, 'updated_at'=>$timeStamp);
                            $servingInserted = array();
                            foreach ($fooddata as $key => $value) {
                                if($key == 'serving'){
                                    $servArray = explode(' ', $servingSize[0]);
                                    $length = count($servArray); 
                                    $servData = $this->getUnitWithTag($servArray[$length - 1]);
                                    $servingInserted['size'] = $servArray[$length - 2]; 
                                    $servingInserted['name'] = $servData['unit'];
                                    $servingInserted['tags'] = $servData['tags']; 
                                    $servingInserted['created_at'] = $timeStamp;
                                    $servingInserted['updated_at'] = $timeStamp;
                                }
                                elseif($key != ''){
                                    $foodInserted[$key] = $value;
                                }
                            }
                            $serId = MpServingSize::insertGetId($servingInserted);
                            $foodInserted['serving_size'] = $serId;
                            $foodId = MpFoods::insertGetId($foodInserted);
                            $isError = false;
                            $rowNumb ++;
                        }
                        elseif($foodId){
                            $servingInserted = array();
                            $servArray = explode(' ', $servingSize[0]);
                            $unitName = substr($servingSize[1], -1);
                            $quantity = filter_var($servingSize[1],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                            $servData = $this->getUnitWithTag($unitName);
                            $servingInserted['size'] = $servArray[0]; 
                            $servingInserted['name'] = rtrim($servArray[1], ',');
                            $servingInserted['tags'] = $servData['tags'];
                            $servingInserted['quantity'] = $quantity;
                            $servingInserted['units'] = $unitName;
                            $range = '';
                            if(count($servArray) > 2){
                                for($i = 2; $i < count($servArray); $i++) {
                                    $range .= $servArray[$i].' ';
                                }    
                            }
                            $servingInserted['range'] = $range ;
                            $servingInserted['created_at'] = $timeStamp;
                            $servingInserted['updated_at'] = $timeStamp;

                            $childSerId = MpServingSize::insertGetId($servingInserted);
                            $updatedFood = MpFoods::find($foodId);
                            if(count($updatedFood)){
                                $servChildIds = $updatedFood->serving_size_child;
                                if($servChildIds == '' || $servChildIds == null)
                                    $updatedFood->serving_size_child = $childSerId;
                                else
                                    $updatedFood->serving_size_child .= ','.$childSerId;
                                $updatedFood->update();
                                $isError = false;
                                $rowNumb++;
                            }
                        }
                    }
                }
            }
        }
    }
}
