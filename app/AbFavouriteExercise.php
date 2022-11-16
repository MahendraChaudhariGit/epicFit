<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AbFavouriteExercise extends Model{
    use SoftDeletes;

    protected $table = 'ab_favourite_exercise';

    public function exercises(){
        return $this->belongsTo('App\Exercise');
    }
    
}
