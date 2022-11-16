<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Session;
use DB;

class Product extends Model{
    use SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['business_id', 'name', 'pro_slug','sku_id', 'description', 'logo', 'sale_price', 'tax', 'stock_location', 'stock_level', 'if_ofs_sale', 'if_stock_alert', 'stock_alert', 'history', 'cost_price', 'stock_note','salesTax','expirey_date','contact_id','featured','pro_size','pro_color','pro_color_check'];

    public function business(){
        return $this->belongsTo('App\Business');
    }
	
	public function location(){
        return $this->belongsTo('App\Location');
    }

    public function stockHistories(){
        return $this->hasMany('App\ProductStockHistory', 'psh_product_id');
    }
    public function categories(){
        return $this->belongsToMany('App\Category', 'product_category', 'pc_product_id', 'pc_category_id');
    }
    static function pivotProductTrashedOnly($ProdId){
        return DB::table('product_category')->where('pc_product_id', $ProdId)->whereNotNull('deleted_at')->select('pc_category_id')->get();
    }
    public function getHistoryUiAttribute(){
        if($this->history){
            $dates = explode('-', $this->history);
            $start = Carbon::createFromFormat('Y/m/d', $dates[0]);
            $end = Carbon::createFromFormat('Y/m/d', $dates[1]);
            return dbDateToDateString($start).' - '.dbDateToDateString($end);
        }
        return '';
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($product){
            $product->stockHistories()->delete();

            DB::table('product_category')->where('pc_product_id', $product->id)->update(array('deleted_at' => createTimestamp()));
        });
        static::deleted(function(){
            if(!Product::OfBusiness()->exists())
                Session::forget('ifBussHasProducts');
        });
    }

    public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->where('business_id', $bussId);
    }

    static function findProd($prodId, $bussId = 0){
        return Product::OfBusiness($bussId)->find($prodId);
    }

    static function findOrFailProd($prodId, $bussId = 0){
        return Product::OfBusiness($bussId)->findOrFail($prodId);
    }

    public function sizeNameArray($size){
        $sizes = [];
        if($size){
            $sizeArray = explode(',', $size);
            if(count($sizeArray)){
                $sizeData = ProductSize::select('id','name','gender')->whereIn('id',$sizeArray)->get();
                if($sizeData->count()){
                    foreach ($sizeData as $value) {
                        $sizes[] = $value->name.'('.$value->gender.')';
                    } 
                }
            }
        }
        return $sizes;
    }

    public function sizeName($size){
        $sizeString = '';
        if($size){
            $sizeString = implode(', ', $this->sizeNameArray($size));
        }
        return $sizeString;
    }
}
