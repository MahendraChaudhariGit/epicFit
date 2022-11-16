<?php
namespace App\Http\Traits;

use App\MpServingSize;
use App\MpMealCategory;

trait MpPlannerTrait{

    /**
     * Get Serving size 
     * 
     * @param
     * @return string 
    **/
    protected function getServingSize(){
        $servingSize = MpServingSize::all();
        $servSize = array('' => '-- Select --');
        if(count($servingSize)){
            foreach ($servingSize as $serving) {
                $servSize[$serving->id] = $serving->size.' '. $serving->name;
            } 
        }
        return $servSize;
    }

    /**
     * Meal category
     *
     * @param void
     * @return Array Category
     */
    protected function mealCategory(){
        $mealCatory = array(''=>'-- Select --');
        $mealsCategory = MpMealCategory::select('id', 'name')->get(); 
        if($mealsCategory->count()){
            foreach ($mealsCategory as $category) {
                $mealCatory[$category->id] = $category->name; 
            }
        }
        return $mealCatory;
    }


    /**
     * Get nutartional data with well formate 
     *
     * @param Arrayv foods[]
     * @return Array nurational data
     */
    protected function getNutritionalInfo($foods){
        $nutrInfo = array();
        $i = 0; 
        foreach ($foods as $value) {
            if($i == 0){
                $nutrInfo = $this->getNutritionalInfoFirst($value);
            }
            else{
                $nutrInfo['water'] += $value->water;
                $nutrInfo['energy'] += $value->energ_kcal;
                $nutrInfo['protein'] += $value->protein;
                $nutrInfo['lipid_total'] += $value->lipid_total;
                $nutrInfo['ash'] += $value->ash;          
                $nutrInfo['carbohydrate'] += $value->carbohydrate;
                $nutrInfo['fiber'] += $value->fiber;
                $nutrInfo['sugar'] += $value->sugar;
                $nutrInfo['calcium'] += $value->calcium;
                $nutrInfo['iron'] += $value->iron;
                $nutrInfo['magnesium'] += $value->magnesium;
                $nutrInfo['phosphorus'] += $value->phosphorus;
                $nutrInfo['potassium'] += $value->potassium;
                $nutrInfo['sodium'] += $value->sodium;
                $nutrInfo['zinc'] += $value->zinc;
                $nutrInfo['copper'] += $value->copper;
                $nutrInfo['manganese'] += $value->manganese;
                $nutrInfo['selenium'] += $value->selenium;
                $nutrInfo['vit_c'] += $value->vit_c;
                $nutrInfo['thiamin'] += $value->thiamin;
                $nutrInfo['riboflavin'] += $value->riboflavin;
                $nutrInfo['niacin'] += $value->niacin;
                $nutrInfo['panto_acid'] += $value->panto_acid;
                $nutrInfo['vit_b6'] += $value->vit_b6;
                $nutrInfo['folate'] += $value->folate;
                $nutrInfo['folic_acid'] += $value->folic_acid;
                $nutrInfo['food_folate'] += $value->food_folate;
                $nutrInfo['folate_dfe'] += $value->folate_dfe;
                $nutrInfo['choline'] += $value->choline;
                $nutrInfo['vit_b12'] += $value->vit_b12;
                $nutrInfo['vit_aiu'] += $value->vit_aiu;
                $nutrInfo['vit_arae'] += $value->vit_arae;
                $nutrInfo['retinol'] += $value->retinol;
                $nutrInfo['alphacarot'] += $value->alphacarot;
                $nutrInfo['beta_carot'] += $value->beta_carot;
                $nutrInfo['beta_crypt'] += $value->beta_crypt;
                $nutrInfo['lycopene'] += $value->lycopene;
                $nutrInfo['lut_zea'] += $value->lut_zea;
                $nutrInfo['vit_e'] += $value->vit_e;
                $nutrInfo['vit_dmcg'] += $value->vit_dmcg;
                $nutrInfo['vivit_diu'] += $value->vivit_diu;
                $nutrInfo['vit_k'] += $value->vit_k;
                $nutrInfo['fa_sat'] += $value->fa_sat;
                $nutrInfo['fa_mono'] += $value->fa_mono;
                $nutrInfo['fa_poly'] += $value->fa_poly;
                $nutrInfo['cholestrl'] += $value->cholestrl;
            }
            $i++;
        } 

        return $nutrInfo;
    }

    /**
     * Get nutartional data with well formate 
     *
     * @param Arrayv foods[]
     * @return Array nurational data
     */
    protected function getNutritionalInfoFirst($value){
        $nutrInfo = array();
        $nutrInfo['water'] = $value->water;
        $nutrInfo['energy'] = $value->energ_kcal;
        $nutrInfo['protein'] = $value->protein;
        $nutrInfo['lipid_total'] = $value->lipid_total;
        $nutrInfo['ash'] = $value->ash;          
        $nutrInfo['carbohydrate'] = $value->carbohydrate;
        $nutrInfo['fiber'] = $value->fiber;
        $nutrInfo['sugar'] = $value->sugar;
        $nutrInfo['calcium'] = $value->calcium;
        $nutrInfo['iron'] = $value->iron;
        $nutrInfo['magnesium'] = $value->magnesium;
        $nutrInfo['phosphorus'] = $value->phosphorus;
        $nutrInfo['potassium'] = $value->potassium;
        $nutrInfo['sodium'] = $value->sodium;
        $nutrInfo['zinc'] = $value->zinc;
        $nutrInfo['copper'] = $value->copper;
        $nutrInfo['manganese'] = $value->manganese;
        $nutrInfo['selenium'] = $value->selenium;
        $nutrInfo['vit_c'] = $value->vit_c;
        $nutrInfo['thiamin'] = $value->thiamin;
        $nutrInfo['riboflavin'] = $value->riboflavin;
        $nutrInfo['niacin'] = $value->niacin;
        $nutrInfo['panto_acid'] = $value->panto_acid;
        $nutrInfo['vit_b6'] = $value->vit_b6;
        $nutrInfo['folate'] = $value->folate;
        $nutrInfo['folic_acid'] = $value->folic_acid;
        $nutrInfo['food_folate'] = $value->food_folate;
        $nutrInfo['folate_dfe'] = $value->folate_dfe;
        $nutrInfo['choline'] = $value->choline;
        $nutrInfo['vit_b12'] = $value->vit_b12;
        $nutrInfo['vit_aiu'] = $value->vit_aiu;
        $nutrInfo['vit_arae'] = $value->vit_arae;
        $nutrInfo['retinol'] = $value->retinol;
        $nutrInfo['alphacarot'] = $value->alphacarot;
        $nutrInfo['beta_carot'] = $value->beta_carot;
        $nutrInfo['beta_crypt'] = $value->beta_crypt;
        $nutrInfo['lycopene'] = $value->lycopene;
        $nutrInfo['lut_zea'] = $value->lut_zea;
        $nutrInfo['vit_e'] = $value->vit_e;
        $nutrInfo['vit_dmcg'] = $value->vit_dmcg;
        $nutrInfo['vivit_diu'] = $value->vivit_diu;
        $nutrInfo['vit_k'] = $value->vit_k;
        $nutrInfo['fa_sat'] = $value->fa_sat;
        $nutrInfo['fa_mono'] = $value->fa_mono;
        $nutrInfo['fa_poly'] = $value->fa_poly;
        $nutrInfo['cholestrl'] = $value->cholestrl;
     
        return $nutrInfo;
    }

}