<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $guarded = [];

    public function mainCategory(){
        return $this->belongsTo('App\MainCategory');
    }
}
