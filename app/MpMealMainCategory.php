<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpMealMainCategory extends Model
{
    protected $guarded = [];
    protected $table ='mpn_meal_main_categories';

    public function subCategory(){
        return $this->belongsTo('App\SubCategory');
    }

}

