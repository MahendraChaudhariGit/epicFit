<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ClassCat extends Model{
	use SoftDeletes;
	protected $table = 'class_cats';
	protected $primaryKey = 'clcat_id';

    public function business(){
        return $this->belongsTo('App\Business', 'clcat_business_id');
    }

    public function scopeOfBusiness($query){
            return $query->where('clcat_business_id', Session::get('businessId'));
    }
}
