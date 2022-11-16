<?php 
namespace App;
use DB;
use Session;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberShipCategory extends Model{
    use SoftDeletes;
    
    protected $table = 'membership_category';
    protected $primaryKey = 'id';
    protected $fillable = [
   
    'mc_category_value',
    'mc_businesses_id',
    
    ];

    public function scopeOfBusiness($query){
            return $query->where('mc_businesses_id', Session::get('businessId'));
    }
   
}