<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStockHistory extends Model{
	use SoftDeletes;

    protected $table = 'product_stock_histories';
    protected $primaryKey = 'psh_id';
    protected $dates = ['created_at'];

    public function product(){
        return $this->belongsTo('App\Product', 'psh_product_id');
    }
}
