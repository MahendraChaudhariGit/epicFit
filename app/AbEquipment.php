<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Session;

class AbEquipment extends Model{
    use SoftDeletes;

    protected $table = 'ab_equipment';
    protected $primaryKey = 'id';
    protected $fillable = ['eq_name'];
    // public function exercises(){
    //     return $this->belongsToMany('App\Exercise', 'ab_exercise_resources', 'resource_id', 'exercise_id');
    // }

    /*public function scopeOfBusiness($query){
            return $query->where('cat_business_id', Session::get('businessId'));
    }*/
    
}
