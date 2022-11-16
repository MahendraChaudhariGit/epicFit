<?php
namespace App\Http\Controllers\Setings\Product;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Product;
use App\Business;
use App\Location;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;

class ProductController extends Controller{
    public function index(){
		if(!Auth::user()->hasPermission(Auth::user(), 'list-product'))
            return redirect('unauthorized');

        $allProducts = array();
        if(Session::has('businessId'))
             $allProducts = Business::find(Session::get('businessId'))->products;
         
        return view('Settings.product.index', compact('allProducts'));
    }

    public function uploadFile(Request $request){
        $prodId = (int)$request->id;
        $prod = Product::find($prodId);
        $prod->update(array('logo' => $request->photoName));
        return url('/uploads/thumb_'.$request->photoName);
    }

    public function store(Request $request){
        $isError = false;
        $msg = [];

        if(!Auth::user()->hasPermission(Auth::user(), 'create-product')){
            if($request->ajax())
                $isError = true;
            else
                return redirect('unauthorized');
        }

        if(!$isError){
            $insertData = array('name' => $request->name, 'sku_id' => $request->sku_id, 'description' => $request->description, 'logo' => $request->productImage, 'sale_price' => $request->sale_price, 'tax' => $request->tax, 'stock_location' => $request->stock_location, 'stock_level' => $request->stock_amount_total, 'history' => $request->history, 'cost_price' => $request->cost_price, 'stock_note' => $request->stock_note);
            if($request->stock_amount_total != 'Unlimited'){
                if(isset($request->if_ofs_sale))
                    $insertData['if_ofs_sale'] = $request->if_ofs_sale;
                if(isset($request->if_stock_alert)){
                    $insertData['if_stock_alert'] = $request->if_stock_alert;
                    $insertData['stock_alert'] = $request->stock_alert;
                }
            }

            $business = Business::find($request->businessId);
            $addedprod = $business->products()->create($insertData);
    		Session::put('ifBussHasProducts' , true); 

            $input = $request->all();
            ksort($input);
            $stock_add_amount = $stock_add_reason = $stock_remove_amount = $stock_remove_reason = [];
            foreach($input as $key => $value){
                if(strpos($key, 'stock_add_amount') !== false)
                    $stock_add_amount[] = $value;
                
                else if(strpos($key, 'stock_add_reason') !== false)
                    $stock_add_reason[] = $value;

                else if(strpos($key, 'stock_remove_amount') !== false)
                    $stock_remove_amount[] = $value;

                else if(strpos($key, 'stock_remove_reason') !== false)
                    $stock_remove_reason[] = $value;
            }
            $insertData = array();
            if(count($stock_add_amount)){
                for($i=0; $i<count($stock_add_amount); $i++)
                    $insertData[] = array('sl_product_id' => $addedprod->id, 'sl_amount' => $stock_add_amount[$i], 'sl_reason' => $stock_add_reason[$i], 'sl_created_at' => 'now()');
            }
            if(count($stock_remove_amount))
                for($i=0; $i<count($stock_remove_amount); $i++){
                    $insertData[] = array('sl_product_id' => $addedprod->id, 'sl_amount' => $stock_remove_amount[$i], 'sl_reason' => $stock_remove_reason[$i], 'sl_created_at' => 'now()');
            }
            if(count($insertData))
                DB::table('stock_levels')->insert($insertData);

            $msg['status'] = 'added';
            $msg['insertId'] = $addedprod->id;
        }
        return json_encode($msg);
    }

    public function show($id){
        if(!Auth::user()->hasPermission(Auth::user(), 'view-product'))
            return redirect('unauthorized');

		$product = Product::findOrFail($id);
        $product->stockLocations = Location::getLocation($product->stock_location);
		return view('Settings.product.show', compact('product'));
    }

    public function edit($id){
        if(!Auth::user()->hasPermission(Auth::user(), 'edit-product'))
            return redirect('unauthorized');

        if(!Session::has('businessId'))
            return Redirect::to('settings/business/create');

        $product = Product::find($id);
        if($product){
            $business = Business::with('locations')->find(Session::get('businessId'));
            $businessId = $business->id;

            $locs = array('' => '-- Select --');
            if($business->locations->count())
                foreach($business->locations as $location)
                    $locs[$location->id] = $location->location_training_area;

            return view('Settings.product.edit', compact('product', 'businessId', 'locs'));
        }
    }

    public function update($id, Request $request){
        $isError = false;
        $msg = [];

        if(!Auth::user()->hasPermission(Auth::user(), 'edit-product')){
            if($request->ajax())
                $isError = true;
            else
                return redirect('unauthorized');
        }

        if(!$isError){
            $product = Product::find($id);
            if($product){
                $product->name = $request->name;
                $product->sku_id = $request->sku_id;
                $product->description = $request->description;
                $product->logo = $request->productImage;
                $product->sale_price = $request->sale_price;
                $product->tax = $request->tax;
                $product->stock_location = $request->stock_location;
                $product->stock_level = $request->stock_amount_total;
                $product->history = $request->history;
                $product->cost_price = $request->cost_price;
                $product->stock_note = $request->stock_note;

                if($request->stock_amount_total != 'Unlimited'){
                    if(isset($request->if_ofs_sale) && $request->if_ofs_sale)
                        $product->if_ofs_sale = $request->if_ofs_sale;
                    else
                        $product->if_ofs_sale = 0;

                    if(isset($request->if_stock_alert) && $request->if_stock_alert){
                        $product->if_stock_alert = $request->if_stock_alert;
                        $product->stock_alert = $request->stock_alert;
                    }
                    else{
                        $product->if_stock_alert = 0;
                        $product->stock_alert = '';
                    }
                }
                
                $product->save();           

                $msg['status'] = 'updated';
            }
        }
        return json_encode($msg);
    }

    public function create(){
        if(!Auth::user()->hasPermission(Auth::user(), 'create-product'))
            return redirect('unauthorized');

        if(!Session::has('businessId'))
            return Redirect::to('settings/business/create');

        $business = Business::with('locations')->find(Session::get('businessId'));
        $businessId = $business->id;

        $locs = array('' => '-- Select --');
        if($business->locations->count())
            foreach($business->locations as $location)
                $locs[$location->id] = $location->location_training_area;

        return view('Settings.product.edit', compact('businessId', 'locs'));
    }
    
    public function destroy($id){
    }
}
