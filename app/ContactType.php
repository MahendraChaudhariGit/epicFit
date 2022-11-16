<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ContactType extends Model{
	use SoftDeletes;
	protected $table = 'contact_types';
	protected $primaryKey = 'ct_id';

    public function business(){
        return $this->belongsTo('App\Business', 'ct_business_id');
    }

    public function scopeOfBusiness($query){
            return $query->where('ct_business_id', Session::get('businessId'));
    }
}
