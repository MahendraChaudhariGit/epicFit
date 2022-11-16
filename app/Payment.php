<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\Http\Traits\HelperTrait;

class Payment extends Model{
	 use SoftDeletes, HelperTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment';
    protected $primaryKey = 'pay_id';

    public function client(){
        return $this->belongsTo('App\Clients','inv_client_id');
    }
	
	public function invoice(){
        return $this->belongsTo('App\Invoice', 'pay_invoice_id', 'inv_id');
    }

	public function area(){
        return $this->belongsTo('App\LocationArea','inv_area_id');
    }
	

	
	public function invoiceitem(){
        return $this->hasMany('App\InvoiceItems', 'inp_invoice_id');
    }

    
	/**
     * save Invoice Items into Invoice item table.
	*/
	 
	static function invoiceItems($input, $invoiceId){
        ksort($input);
		$productName = $staffId = $productQuantity = $productPrice = $productTax = $productTotal = $productId = $type = [];
        foreach($input as $key => $value){
            if(strpos($key, 'productName') !== false)
                $productName[] = $value;
            
            else if(strpos($key, 'staffName') !== false)
                $staffId[] = $value;

            else if(strpos($key, 'quantity') !== false)
                $productQuantity[] = $value;

            else if(strpos($key, 'unit_price') !== false)
                $productPrice[] = $value;
				
			else if(strpos($key, 'tax') !== false)
                $productTax[] = $value;
				
			else if(strpos($key, 'item-total') !== false)
                $productTotal[] = $value;
			
				
			else if(strpos($key, 'productId') !== false)
                $productId[] = $value;
				
			else if(strpos($key, 'serviceId') !== false)
				if($value != ''){
					$type[] = 'appointment';
				} else {
                	$type[] = 'product' ;
				}
			
        }
		
		$insertData = array();
        if(count($productName)){
            for($i=0; $i<count($productName); $i++){

                if($productName[$i] != ''){
                    $timestamp = date('Y-m-d H:i:s');
                    $insertData[] = array('inp_invoice_id' => $invoiceId, 'inp_item_desc' => $productName[$i], 'inp_staff_id' => $staffId[$i], 'inp_price' => $productPrice[$i], 'inp_quantity' => $productQuantity[$i],'inp_tax' => $productTax[$i],'inp_total' => $productTotal[$i], 'created_at' => $timestamp, 'updated_at' => $timestamp,'inp_type' => $type[$i],'inp_product_id' =>$productId[$i] );
                }
            }
        }
	
        if(count($insertData))
            return DB::table('invoice_items')->insert($insertData);

        return false;
    }

    /**
     * Return epic credit to client
     * @param
     * @return
    **/
    static function epicCreditReturn($payment){
        $clientId = $payment->invoice->inv_client_id;
        $makeup = new Makeup;
        $makeup->makeup_client_id = $payment->invoice->inv_client_id;
        $makeup->makeup_amount = $payment->pay_amount;
        $makeup->makeup_purpose = 'invoice_amount';
        $makeup->makeup_user_id = $makeup->UserInformation['id']; 
        $makeup->makeup_user_name = $makeup->UserInformation['name'];
        if($makeup->save()){
            if($payment->invoice->client->count()){
                $epic_bal = $payment->invoice->client->makeups()->sum('makeup_amount');
                $payment->invoice->client->epic_credit_balance = $epic_bal;
                $payment->invoice->client->save();
            }
        }
    }

    /**
     * invoice delete event
     * @param invoice
     * @return void
    **/
    protected static function boot(){
        parent::boot();
        static::deleting(function($payment){
            \App\Invoice::where('inv_id',$payment->pay_invoice_id)->update(['inv_status' => 'Unpaid']);
            Payment::epicCreditReturn($payment);
        });
        static::deleted(function(){
            
        });
    }

}
