<?php

namespace App\Imports;

use App\MpMealCategory;
use App\MpMeals;
use App\MpTags;
use App\Staff;
use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MealImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row){
            /* Nutritional Informatio */
            $nutritionalInformation = '{"energ_kcal":"'.$row['calories'].'","fat":"'.$row['fat'].'","fa_sat":"'.$row['saturated_fat'].'","carbohydrate":"'.$row['carbohydrate'].'","sugar":"'.$row['sugar'].'","sodium":"'.$row['sodium'].'","fiber":"'.$row['fiber'].'","protein":"'.$row['protein'].'","cholesterol":"'.$row['cholesterol'].'"}';

            /* Get Staff Id */
            $staffId = Staff::where(DB::raw('concat(first_name," ",last_name)'), 'like', "%".$row['staff']."%")->pluck('id')->first();

            /* Set Time */
            $time = (isset($row['timehr'])?$row['timehr']*60:0) +  (isset($row['timemin'])?$row['timemin']:0);

            /* Get Tips */
            if(isset($row['tips'])){
                $tips = str_replace(':','<br><br>',$row['tips']);
            }else{
                $tips = '';
            }
            $mealData = [
                'name' => $row['name'],
                'business_id' => session()->get('businessId'),
                'staff_id' => $staffId,
                'description' => $row['description'],
                'ingredients' => str_replace(':','<br><br>',$row['ingredients']),
                'method' => str_replace(':','<br><br>',$row['preparation']),
                'tips' => $tips,
                'serves' => $row['serves'],
                'time' => $time,
                'nutritional_information' => $nutritionalInformation,
            ];
            $meal = MpMeals::create($mealData);
            $tags = explode(',',$row['tags']);
            $mealTags = [];
            foreach ($tags as $tag) {
                $mealTags[] = ['mp_id'=>$meal->id, 'mp_type'=>'meal','mp_tag_name'=>trim($tag)];
            }
            MpTags::insert($mealTags);

            $catagoriesExplode = explode(',',$row['categories']);
            $categories = array();
            foreach($catagoriesExplode as $data){
                $categories[] = trim($data);
            }
            $categoriesId = array();
            foreach($categories as $category){
                $categoriesId[] = MpMealCategory::where('name','like',"%$category%")->pluck('id')->first();
            }
            $meal->categories()->attach($categoriesId);
        }
    }
}


