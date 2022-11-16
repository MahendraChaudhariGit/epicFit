<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpClientMealplanIngrediant extends Model{

    protected $table = 'mpn_client_mealplan_ingrediants';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function mealClientPlan(){
        return $this->belongsTo('App\MpClientMealplan', 'mpn_client_mealplan_id');
    }

}