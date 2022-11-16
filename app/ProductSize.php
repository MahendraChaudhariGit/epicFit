<?php 
namespace App;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSize extends Model{
	use SoftDeletes;
	protected $table = 'product_size';
    protected $primaryKey = 'id';
	protected $fillable = ['name','gender'];



	/*public function products(){
        return $this->belongsToMany('App\Product', 'product_category', 'pc_category_id', 'pc_product_id');
    }*/
	
	public function scopeOfBusiness($query){
            return $query->where('business_id', Session::get('businessId'));
    }

} 
