<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FitnessMap extends Model
{
    protected $table = 'fitness_mapper_routes';
    protected $guarded = [];

    // public function challenge(){
    //     return $this->hasMany('App\Challenge');
    // }
}
