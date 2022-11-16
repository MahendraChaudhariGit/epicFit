<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Session;
use App\Location;
use App\StaffEventSingleService;
use App\StaffEventClass;
use App\InvoiceItems;
use App\Product;

class Invoice extends Model{
	use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoices';
    protected $primaryKey = 'inv_id';
    protected $guarded = [];

    public function scopeOfClient($query, $clientId){
        return $query->where('inv_client_id', $clientId);
    }

    public function client(){
        return $this->belongsTo('App\Clients','inv_client_id');
    }

    public function clientWithTrashed(){
        return $this->client()->withTrashed();
    }

    public function staffWithTrashed(){
        return $this->belongsTo('App\Staff','staffId')->withTrashed();
    }
	
	public function area(){
        return $this->belongsTo('App\LocationArea','inv_area_id');
    }
	
    public function areaWithTrashed(){
        return $this->area()->withTrashed();
    }
	
	public function invoiceitem(){
        return $this->hasMany('App\InvoiceItems', 'inp_invoice_id');
    }
    
    public function locationData($locationId){
        if($locationId){
            $locationdata = Location::withTrashed()->where('id',$locationId)->pluck('location_training_area')->first();
            if($locationdata){
                return $locationdata; 
            }
        }    
        return '-------';
    }

    public function payment(){
        return $this->hasMany('App\Payment', 'pay_invoice_id');
    }

    public function totalPayamount(){
        return $this->payment->sum('pay_amount');
    }

    static function location(){
        if(Session::has('businessId')){
            $business = Business::find(Session::get('businessId'));
            if($business){
                $locations = $business->locations;
                if($locations->count()){
                    foreach($locations as $location){
                        $locationVal[$location->id] = ucfirst($location->location_training_area);
                    }
                } 
            }
            asort($locationVal);
        }
        return $locationVal;
    }

    public function loc(){
        return $this->belongsTo('App\Location','inv_area_id');
    }

	/**
     * save Invoice Items into Invoice item table.
	*/
    static function invoiceItems($input, $invoiceId, $appoitmentCheck = '', $oldStock = []){
        ksort($input);
        $insertData = [];
        $service_id = [];
        $classId = [];
        $newStock = [];
        foreach($input as $key => $value){
            if(strpos($key, 'productName') !== false && $value != ""){
                $index = str_replace('productName','',$key);
                $timestamp = date('Y-m-d H:i:s');

                $type = strtolower($input['productType'.$index]);
                if($type == 'service'){
                    $service_id[] = $input['productId'.$index];
                }
                elseif($type == 'class'){
                   $classId[] = $input['productId'.$index]; 
                }
                
                if(array_key_exists('staffName'.$index, $input) && $input['staffName'.$index] !=''){
                    $staffId = $input['staffName'.$index];
                }
                else{
                    $staffId = 0;
                }

                if(array_key_exists('quantity'.$index, $input) && $input['quantity'.$index] != '')
                    $quantity = $input['quantity'.$index];
                else
                    $quantity = 1;

                if($type == 'product'){
                    $prodId = $input['productId'.$index];
                    $newStock[$prodId] = $quantity;
                    
                }

                $insertData[] = array('inp_invoice_id' => $invoiceId,'inp_item_desc' =>$input['productName'.$index] ,'inp_staff_id'=>$staffId,'inp_price'=>$input['unitPrice'.$index],'inp_quantity'=>$quantity,'inp_tax'=>$input['taxName'.$index] ,'inp_total'=>$input['totalPrice'.$index],'inp_type'=>$type,'inp_product_id'=>$input['productId'.$index],'inp_tax_type'=>$input['taxType'.$index],'created_at' => $timestamp, 'updated_at' => $timestamp);   
            }
        }
    
        if(count($insertData)){
            DB::table('invoice_items')->insert($insertData);

            Invoice::updateProductStock($oldStock, $newStock);
            
            /*if(count($service_id)){
                if($appoitmentCheck){
                    $timestamp = 
                    StaffEventSingleService::withTrashed()->whereIn('sess_id',$service_id)
                    ->where('sess_booking_status','Confirmed')
                    ->whereRaw('sess_start_datetime < now()')
                    ->update(['sess_client_attendance'=>'Attended']);
                }
                StaffEventSingleService::whereIn('sess_id',$service_id)->update(['sess_invoice_status'=>'YES']); 
            }
            if(count($classId)){
                if($appoitmentCheck){
                    $classBookings = StaffEventClass::withTrashed()->whereIn('sec_id',$classId)->whereRaw('sec_start_datetime < now()')->get();
                    if($classBookings->count()){
                        $classBookings = $classBookings->pluck('sec_id')->toArray();
                        DB::table('staff_event_class_clients')->whereIn('secc_sec_id',$classBookings)->where('secc_client_id',$input['clientId'])->update(['secc_client_attendance'=>'Attended']);
                    }
                }

                DB::table('staff_event_class_clients')->whereIn('secc_sec_id',$classId)->where('secc_client_id',$input['clientId'])->update(['secc_invoice_status'=>'YES']);
            }*/
            return true;
        }

        return false;
    }


    /**
     * Delete all invoice item which belongs to invoice
     * @param invoice id, force delete(true/false)
     * @return void
    **/
    static function deleteInvoiceItem($invId, $force = false){
        $invoiceItemsInfo = InvoiceItems::where('inp_invoice_id',$invId)->get();
        if($invoiceItemsInfo->count()){
            /*$serviceBookings = $invoiceItemsInfo->where('inp_type', 'service');
            if($serviceBookings->count()){
                $serviceBookings = $serviceBookings->pluck('inp_product_id')->toArray();
                StaffEventSingleService::whereIn('sess_id',$serviceBookings)->update(['sess_invoice_status'=>'NO']); 
            }*/

            if($force)
                InvoiceItems::where('inp_invoice_id',$invId)->forceDelete();
            else
                InvoiceItems::where('inp_invoice_id',$invId)->delete();
        }
    }


    /**
     * invoice delete event
     * @param invoice
     * @return void
    **/
    protected static function boot(){
        parent::boot();
        static::deleting(function($invoice){
            Invoice::deleteInvoiceItem($invoice->inv_id);
            $payments = \App\Payment::where('pay_invoice_id', $invoice->inv_id)->get();
            if($payments->count())
                foreach ($payments as  $payment)
                    $payment->delete();
                
            \App\InvoiceEmailLog::where('iel_invoice_id', $invoice->inv_id)->delete();
        });
        static::deleted(function(){
            
        });
    }


    /**
     *
     * @param int $productid
     * @param int $oldStock
     * @param int $newStock
     *
     * @return void
     */
    static function updateProductStock($oldStock, $newStock){
        $updateData = array();
        if(count($oldStock)){
            foreach ($newStock as $key => $value) {
                if(array_key_exists($key, $oldStock)){
                    if($oldStock[$key] > $newStock[$key]){
                        $updateStock = -($oldStock[$key] - $newStock[$key]);
                    }
                    elseif($oldStock[$key] < $newStock[$key]){
                        $updateStock = $newStock[$key];
                    }
                    else{
                        $updateStock = 0;
                    }
                    $updateData[$key] =  $updateStock;
                }
                else{
                    $updateData[$key] =  $newStock[$key];
                }
            }
        }
        else{
            $updateData = $newStock;
        }

        if(count($updateData)){
            $prIds = array_keys($updateData);
            $products = Product::whereIn('id', $prIds)->get();
            foreach ($products as $product) {
                if($product->stock_level != 'Unlimited'){
                    $productStock = (int)$product->stock_level;
                        $product->stock_level = $productStock - $updateData[$product->id];

                    $product->update();
                }
            } 
        }
    }
}
