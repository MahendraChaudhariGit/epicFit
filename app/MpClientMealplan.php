<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MpClientMealplan extends Model{
	use SoftDeletes;

    const MORNING_SNACK = 1, EVENING_SNACK = 2, NIGHT_SNACK = 3;
    const UNHEALTHY = 1, AVERAGE = 2, HEALTHY = 3;
    const SMALL = 1, MEDIUM = 2, LARGE = 3;
    protected $table = 'mpn_client_mealplan';
    protected $primaryKey = 'id';
    protected $guarded = [];


	public function food(){
	    return $this->belongsTo('App\MpFoods', 'event_id', 'id');
	}

    public function meal(){
        return $this->belongsTo('App\MpMeals', 'event_id', 'id');
    }

    public function category(){
        return $this->belongsTo('App\MpMealCategory', 'event_meal_category', 'id')->withTrashed();
    }
}