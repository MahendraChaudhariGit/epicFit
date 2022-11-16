<?php 
namespace App;
use DB;
use Session;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeCategory extends Model{
    use SoftDeletes;
    
    protected $table = 'income_categories';
    protected $fillable = [
   
    'category_name',
    'business_id',
    
    ];

    public function scopeOfBusiness($query){
            return $query->where('business_id', Session::get('businessId'));
    }
   
}