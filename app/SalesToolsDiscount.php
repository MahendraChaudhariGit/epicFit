<?php 
namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;


class SalesToolsDiscount extends Model{
	use SoftDeletes;
    
    protected $table = 'sales_tools_discounts';
    protected $primaryKey = 'std_id';

    public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->where('std_business_id', $bussId);
    }

    static function findOrFailDiscount($discountId, $bussId = 0){
        return SalesToolsDiscount::OfBusiness($bussId)->findOrFail($discountId);
    }

    public function tax(){
        return $this->belongsTo('App\MemberShipTax','std_tax');
    }
}

?>