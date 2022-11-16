<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MpFoods extends Model{
	use SoftDeletes;
	
    protected $table = 'mpn_food';
    protected $primaryKey = 'id';

    protected $fillable = ['food_img','serving_size','name','brand','supplier','supplier_id','description','water','energ_kcal','protein','lipid_total','ash','carbohydrate','fiber','sugar','calcium','iron','magnesium','phosphorus','potassium','sodium','zinc','copper','manganese','selenium','vit_c','thiamin','riboflavin','niacin','panto_acid','vit_b6','folate','folic_acid','food_folate','folate_dfe','folate_dfe','choline','vit_b12','vit_aiu','vit_arae','retinol','alphacarot','beta_carot','beta_crypt','lycopene','lut_zea','vit_e','vit_dmcg','vivit_diu','vit_k','fa_sat','fa_mono','fa_poly','cholestrl','priority','is_drink'];
    

    /*public function servings(){
	    return $this->belongsToMany('App\MpServingSize','mp_serving_food','food_id','servingsize_id')->withPivot('');
	}*/

	public function servingsize(){
		return $this->belongsTo('App\MpServingSize','serving_size','id');
	}

	public function shoppingcategory(){
		return $this->belongsTo('App\MpShoppingCategory','shopping_category','id');	
	}
}
	 	 	 	 	 	 