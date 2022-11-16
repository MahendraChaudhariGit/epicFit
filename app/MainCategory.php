<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $guarded = [];

    public function subCategory(){
        return $this->hasMany('App\SubCategory');
    }
}
