<?php 
namespace App;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model{
	use SoftDeletes;
	protected $table = 'category';
    protected $primaryKey = 'cat_id';
	protected $fillable = ['cat_name','cat_image','cat_sub_title','cat_slug'];



	public function products(){
        return $this->belongsToMany('App\Product', 'product_category', 'pc_category_id', 'pc_product_id');
    }
	
	public function scopeOfBusiness($query){
            return $query->where('cat_business_id', Session::get('businessId'));
    }

} 
