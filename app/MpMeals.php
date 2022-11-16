<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class MpMeals extends Model{
	use SoftDeletes;

    const SHOW = 0,HIDE = 1;
    protected $table = 'mpn_meal';
    protected $primaryKey = 'id';
    private $ratingCount = null;
    protected $fillable = ['business_id','serving_id','serves','name','description','tips','method','ingredients','time','staff_id','nutritional_information','listing_status'];
  
    public static $ingredientMeasurement = [
        'cup',
        'teaspoon',
        'tablespoon',
        'bunch',
        'cake',
        'dash',
        'drop',
        'gallon',
        'gram',
        'handful',
        'liter',
        'milliliter',
        'ounce',
        'packet',
        'piece',
        'pinch',
        'pint',
        'pound',
        'quart',
        'shot',
        'splash',
        'sprig',
        'kilogram',
        'cubic inch',
        'fluid ounce',
        'dessert spoon',
        'serving',
        'jumbo',
        'whole',
        'roll',
        'slice',
        'can',
        'bottle',
        'package',
        'scoop',
        'tub',
        'cone',
        'dozen',
        'unit',
        'gross',
        'inch',
        'jar',
        'cube',
        'box',
        'round',
        'loaf',
        'stick',
        'hunk',
        'fillet',
        'root',
        'chunk',
        'sliver',
        'scape',
        'bulb',
        'head',
        'clove',
        'fingerling',
        'baby',
        'chip',
        'bag',
    ];

    
    // public static $ingredientMeasurement = [
    //     ["name"=> "cup","value"=> "1"],
    //     ["name"=> "teaspoon","value"=> "2"],
    //     ["name"=> "tablespoon","value"=> "3"],
    //     ["name"=> "bunch","value"=> "4"],
    //     ["name"=> "cake","value"=> "5"],
    //     ["name"=> "dash","value"=> "6"],
    //     ["name"=> "drop","value"=> "7"],
    //     ["name"=> "gallon","value"=> "8"],
    //     ["name"=> "gram","value"=> "9"],
    //     ["name"=> "handful","value"=> "10"],
    //     ["name"=> "liter","value"=> "11"],
    //     ["name"=> "milliliter","value"=> "12"],
    //     ["name"=> "ounce","value"=> "13"],
    //     ["name"=> "packet","value"=> "14"],
    //     ["name"=> "piece","value"=> "15"],
    //     ["name"=> "pinch","value"=> "16"],
    //     ["name"=> "pint","value"=> "17"],
    //     ["name"=> "pound","value"=> "18"],
    //     ["name"=> "quart","value"=> "19"],
    //     ["name"=> "shot","value"=> "20"],
    //     ["name"=> "splash","value"=> "21"],
    //     ["name"=> "sprig","value"=> "22"]
    // ];

    public function scopeActive(){
        return $this->where('listing_status',$this::SHOW);
    }

	public function categories(){
		 return $this->belongsToMany('App\MpMealCategory','mpn_meal_cat', 'meal_id','cat_id');	
	}

	public function mealimages(){
        return $this->hasMany('App\MpMealimages','mmi_meal_id','id');
    }

    public function foods(){
        return $this->belongsToMany('App\MpFoods', 'mpn_meal_food', 'meal_id', 'food_id');
    }
    
    public function tags(){
        return $this->hasMany('App\MpTags', 'mp_id', 'id')->where('mp_type','meal');
    }
    public function staff(){
        return $this->belongsTo('App\Staff','staff_id');
    }

    public function mealMainCategory(){
        return $this->hasMany('App\MpMealMainCategory','mp_meal_id');
    }
    public function mealMainCategoryDetails(){
        return $this->hasMany('App\MpMealMainCategory','mp_meal_id')->with('subCategory');
    }

    public function mealIngredient(){
        return $this->hasMany('App\MpIngredientTag','mp_meal_id');
    }

    public function mealIngredientTag(){
        return $this->hasMany('App\MpIngredientTag','mp_meal_id');
    }

    public function mealMealIngredientSet(){
        return $this->hasMany('App\MpMealIngredientSet','mp_meal_id');
    }
/* new */
    public function mealIngredientSetPart1(){
        return $this->hasMany('App\MpMealIngredientSet','mp_meal_id')->where('part',1);
    }

    public function mealPreparationPart1(){
        return $this->hasMany('App\MpMealPreparation','mp_meal_id')->where('part',1);
    }

    public function mealIngredientSetPart2(){
        return $this->hasMany('App\MpMealIngredientSet','mp_meal_id')->where('part',2);
    }

    public function mealPreparationPart2(){
        return $this->hasMany('App\MpMealPreparation','mp_meal_id')->where('part',2);
    }

    public function mealEdamamIngredient(){
        return $this->hasMany('App\MpMealEdamamIngredient','mp_meal_id');
    }

    public function mealMealCat(){
        return $this->hasMany('App\MpMealCat','meal_id');
    }

    public function clientMealplan(){
        return $this->hasMany('App\MpClientMealplan','event_id')->where('client_id',Auth::User()->account_id);
    }

    public function mealReview(){
        return $this->hasMany('App\RecipeReview','meal_id')->with('replyRecipeReview');
    }

    public function rating(){
        return $this->hasMany('App\RecipeRating', 'meal_id', 'id');
    }
    public function getRatingCount(){
        if ($this->ratingCount == null){
            $this->ratingCount = $this->rating()->count();
        }
        return $this->ratingCount;
    }
}