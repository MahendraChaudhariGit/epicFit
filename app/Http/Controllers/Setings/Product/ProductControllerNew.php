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
use App\ProductStockHistory;
use App\Http\Traits\HelperTrait;
use Input;
//use App\Contact;
use Carbon\Carbon;
use App\Category;

class ProductControllerNew extends Controller{
    use HelperTrait;
    private $cookieSlug = 'product';

    public function index(Request $request){
		if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-product'))
            abort(404);

        $allProducts = array();
        $search = $request->get('search');
        //if(Session::has('businessId')){
            $length = $this->getTableLengthFromCookie($this->cookieSlug);
            //$allProducts = Product::where('business_id', Session::get('businessId'))->paginate($length);
            if($search){
                $allProducts = Product::OfBusiness()
                                      ->where(function($query) use($search){
                                            $query->orWhere('name', 'like', "%$search%")
                                                  ->orWhere('sku_id', 'like', "%$search%")
                                                  ->orWhereHas('categories', function($query) use ($search){
                                                        $query->where('cat_name', 'like', "%$search%");
                                                  });
                                      })
                                      ->paginate($length);
            }
            else
                $allProducts = Product::OfBusiness()->paginate($length);
            
        return view('Settings.product.index', compact('allProducts'));
    }

    public function uploadFile(Request $request){
        $prod = Product::find($request->id);
        if($prod){
            $prod->update(array('logo' => $request->logo));
            return url('/uploads/thumb_'.$request->logo);
        }
        return '';
    }

    public function store(Request $request){
        $isError = false;
        $msg = [];

        if($request->businessId != Session::get('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-product')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        
        if(!$isError){
            if($request->expirey_date != '')
                $expireyDate = $request->expirey_date;
            else
                $expireyDate = NULL;

            $contact_id=0;
            if($request->proId != '')
                $contact_id = $request->proId;

            $insertData = array('business_id' => $request->businessId, 'name' => $request->name, 'sku_id' => $request->sku_id, 'description' => $request->description, 'logo' => $request->productImage, 'sale_price' => $request->sale_price, 'tax' => $request->tax, 'salesTax' => $request->salesTax, 'stock_location' => $request->stock_location, 'stock_level' => $request->stockLevel,/* 'history' => $request->history,*/ 'cost_price' => $request->cost_price, 'stock_note' => $request->stock_note,'expirey_date'=>$expireyDate,'contact_id'=>$contact_id);
            
            if($request->stockLevel != 'Unlimited'){
                if(isset($request->if_ofs_sale))
                    $insertData['if_ofs_sale'] = $request->if_ofs_sale;
                if(isset($request->if_stock_alert)){
                    $insertData['if_stock_alert'] = $request->if_stock_alert;
                    $insertData['stock_alert'] = $request->stock_alert;
                }
            }

            if(isset($request->featured) && $request->featured == 1)
                $insertData['featured'] = 1;
            else
                $insertData['featured'] = 0;
            
            if($request->has('pro_size') && count($request->pro_size))
                    $insertData['pro_size'] = implode(',', $request->pro_size);

            if($request->pro_color_check){
                 $insertData['pro_color'] = $request->pro_color;
                 $insertData['pro_color_check'] = 1;
            }
            else
                $insertData['pro_color_check'] = 0;

            if($request->pro_slug != '')
                $slug = $this->makeSlug($request->pro_slug);
            else
                $slug = $this->makeSlug($request->name);

            $insertData['pro_slug'] = $slug;

            //$business = Business::find($request->businessId);
            //$addedprod = $business->products()->create($insertData);
            $addedprod = Product::create($insertData);
    		Session::put('ifBussHasProducts' , true); 

            $this->saveStockLevelHistory(['stockLevelHistory' => $request->stockLevelHistory, 'prodId' => $addedprod->id]);
            $addedprod->categories()->attach($request->pro_category);
            /*$input = $request->all();
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
                DB::table('stock_levels')->insert($insertData);*/

            $msg['status'] = 'added';
            $msg['insertId'] = $addedprod->id;
        }
        return json_encode($msg);
    }

    public function show($id){
        $product = Product::findOrFailProd($id);

        if(!Auth::user()->hasPermission(Auth::user(), 'view-product'))
            abort(404);

		//$product = Product::findOrFail($id);
        $product->stockLocations = Location::getLocation($product->stock_location);
        $proSize = $this->getProductSize();

        $business = Business::with('locations')->find(Session::get('businessId'));
        $businessId = $business->id;
        $locs = array();
        if($business->locations->count()){
            foreach($business->locations as $location)
                $locs[$location->id] = ucfirst($location->location_training_area);
            asort($locs);
        }

		return view('Settings.product.show', compact('product','proSize','locs'));
    }

    public function edit($id){
        $product = Product::findOrFailProd($id);

         //$product = Product::with('categories')->where('id',$id)->first();
        if(!Auth::user()->hasPermission(Auth::user(), 'edit-product'))
            abort(404);

        $catData = $this->getProductCat();
        $pro_cat = $catData['pro_cat'];
        $parentCat=$catData['parentCat'];

        $proSize = $this->getProductSize();

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');
        //$cat=$product->categories()->select('cat_id')->get();
        $pro_category = $product->categories->pluck('cat_id')->toArray();
       
        //$product = Product::find($id);
        //if($product){
            $business = Business::with('locations')->find(Session::get('businessId'));
            $businessId = $business->id;

            $locs = array('' => '-- Select --');
            if($business->locations->count()){
                foreach($business->locations as $location)
                    $locs[$location->id] = ucfirst($location->location_training_area);
                asort($locs);
            }

            $stockHistories = $product->stockHistories()->join('users', 'psh_user_id', '=', 'users.id')->select('users.name', 'product_stock_histories.*')->orderBy('product_stock_histories.created_at', 'DESC')->get();
            //dd($stockHistories);
            //$supplierName='';
           /* $contact = Contact::withTrashed()->find($product->contact_id);
            if($contact){
                $supplierName = $contact->contact_name;
            }*/
            $supplierName = $this->getContactName($product->contact_id);
            $data = explode(',', $product->pro_size);
            $productSize=[];
            if(count($data)){
                foreach ($data as $value) {
                    $productSize[] = (int)$value; 
                }
            }
            
            return view('Settings.product.edit', compact('product', 'businessId', 'locs', 'stockHistories','pro_category','pro_cat','supplierName','parentCat','proSize','productSize'));
        //}
    }

    public function update($id, Request $request){
        $isError = false;
        $msg = [];

        $product = Product::findProd($id, $request->businessId);

        if(!$product || !Auth::user()->hasPermission(Auth::user(), 'edit-product')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        
        if(!$isError){
            //$product = Product::find($id);
            //if($product){expirey_date
                $product->name = $request->name;
                $product->sku_id = $request->sku_id;
                $product->description = $request->description;
                $product->logo = $request->productImage;
                $product->sale_price = $request->sale_price;
                $product->tax = $request->tax;
                $product->salesTax = $request->salesTax;
                $product->stock_location = $request->stock_location;
                $product->stock_level = $request->stockLevel;
                //$product->history = $request->history;
                $product->cost_price = $request->cost_price;
                $product->stock_note = $request->stock_note;
                
                if($request->pro_size != '' && count($request->pro_size))
                    $product->pro_size = implode(',', $request->pro_size);

                if($request->pro_color_check){
                    $product->pro_color = $request->pro_color;
                    $product->pro_color_check = 1;
                }
                else
                    $product->pro_color_check = 0;

                if($request->expirey_date != '')
                    $product->expirey_date = $request->expirey_date;
                else
                    $product->expirey_date = NULL;

                if($request->proId != '')
                    $product->contact_id = $request->proId;

                if($request->stockLevel != 'Unlimited'){
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

                if(isset($request->featured) && $request->featured == 1)
                    $product->featured = 1;
                else
                    $product->featured = 0;

                // update slug according to input
                if($request->old_pro_slug == "" || strcmp($request->pro_slug, $request->old_pro_slug) != 0){
                    if($request->pro_slug != '')
                        $product->pro_slug = $this->makeSlug($request->pro_slug);
                    else
                       $product->pro_slug = $this->makeSlug($request->name); 
                }

                $product->save();
                 
                 if($request->pro_category == '')
                    $prodCateg = [];
                else
                    $prodCateg = $request->pro_category;

                $linkedDeletedProductId = Product::pivotProductTrashedOnly($product->id);
                if(count($linkedDeletedProductId))
                    foreach($linkedDeletedProductId as $linkedDeletedProdId)
                        $prodCateg[] = $linkedDeletedProdId->pc_category_id;
                $product->categories()->sync($prodCateg); 
                /*if($request->pro_category == '')
                    $classAreas = [];
                else
                    $classAreas = $request->classAreas;
                $linkedDeletedAreaIds = Clas::pivotAreasTrashedOnly($class->cl_id);
                if(count($linkedDeletedAreaIds))
                    foreach($linkedDeletedAreaIds as $linkedDeletedAreaId)
                        $classAreas[] = $linkedDeletedAreaId->ac_la_id;
                $class->areas()->sync($classAreas); */   
                     //$product->categories()->attach($request->pro_category);
                



                if($request->stockLevelHistory)
                    $this->saveStockLevelHistory(['stockLevelHistory' => $request->stockLevelHistory, 'prodId' => $id]);

                $msg['status'] = 'updated';
            //}
        }
        return json_encode($msg);
    }

    public function create(){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-product'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');

        $business = Business::with('locations')->find(Session::get('businessId'));
        $businessId = $business->id;

        $locs = array('' => '-- Select --');
        if($business->locations->count()){
            foreach($business->locations as $location)
                $locs[$location->id] = ucfirst($location->location_training_area);
            asort($locs);
        }

        $catData = $this->getProductCat();
        $pro_cat = $catData['pro_cat'];
        $parentCat=$catData['parentCat'];

        $proSize = $this->getProductSize();
            
        return view('Settings.product.edit', compact('businessId', 'locs','pro_cat','parentCat','proSize'));
    }

    protected function saveStockLevelHistory($data){
        $stockLevelHistories = explode('|', $data['stockLevelHistory']);
        $insertData = [];
        foreach($stockLevelHistories as $stockLevelHistory){
            $histData = explode(',', $stockLevelHistory);

            $insertData[] = array('psh_product_id' => $data['prodId'], 'psh_user_id'=> Auth::user()->id, 'psh_text' => $histData[2], 'psh_type' => $histData[0], 'created_at' => $histData[1], 'updated_at' => $histData[1]);
        }
        if(count($insertData))
            ProductStockHistory::insert($insertData);
    }
    
    public function destroy($id){
        $product = Product::findOrFailProd($id);

        if(!isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'delete-product'))
            abort(404);
        
        //$product = Product::find($id);
        //if($product){
            $product->delete();
            return redirect()->back()->with('message', 'success|Product has been deleted successfully.');
            //route('products')
        //}
    }

    
    /**
     * Clone product
     * @param product id
     * @return edit view
    **/
    public function productClone($id){
        if(!Auth::user()->hasPermission(Auth::user(), 'edit-product'))
            abort(404);

        $product = Product::findOrFailProd($id);
        if(count($product)){
            $newRow = $product->replicate();
            if($newRow->save()){
                $catData = $this->getProductCat();
                $pro_cat = $catData['pro_cat'];
                $parentCat=$catData['parentCat'];

                $proSize = $this->getProductSize();

                $pro_category = $product->categories->pluck('cat_id')->toArray();
               
                $business = Business::with('locations')->find(Session::get('businessId'));
                $businessId = $business->id;

                $locs = array('' => '-- Select --');
                if($business->locations->count()){
                    foreach($business->locations as $location)
                        $locs[$location->id] = ucfirst($location->location_training_area);
                    asort($locs);
                }

                $stockHistories = $newRow->stockHistories()->join('users', 'psh_user_id', '=', 'users.id')->select('users.name','product_stock_histories.*')->orderBy('product_stock_histories.created_at', 'DESC')->get();
                    
                $supplierName = $this->getContactName($newRow->contact_id);
            }
        }
            // set clone row as default row in edit form
        $product = $newRow;

        return view('Settings.product.edit', compact('product', 'businessId', 'locs', 'stockHistories','pro_category','pro_cat','supplierName','parentCat','proSize'));
    }


    /**
     * Update field according to request from product view
     * @param form data
     * @return status(update/fail)
    **/
    public function updateField(Request $request){
        $response['status'] = 'error';
        $upField = [];
        if($request->has('name')){
            $upField['name'] = $request->name;
        }
        elseif($request->has('skuid')){
            $upField['sku_id'] = $request->skuid;
        }
        elseif($request->has('disc')){
            $upField['description'] = $request->disc;
        }
        elseif($request->has('saleprice')){
            $upField['sale_price'] = $request->saleprice;
        }
        elseif($request->has('saletax')){
            $upField['salesTax'] = $request->saletax;
        }
        elseif($request->has('stackloc')){
            $upField['stock_location'] = $request->stackloc;
        }
        elseif($request->has('stacklevel')){
            $upField['stock_level'] = $request->stacklevel;
        }
        elseif($request->has('costprice')){
            $upField['cost_price'] = $request->costprice;
        }
        elseif($request->has('tax')){
            $upField['tax'] = $request->tax;
        }
        elseif($request->has('note')){
            $upField['stock_note'] = $request->note;
        }
        elseif($request->has('size')){
            $upField['pro_size'] = implode(',', $request->size);
        }
        elseif($request->has('color')){
            $upField['pro_color'] = $request->color;
        }

        //update field according to id
        if(count($upField) && $request->has('entityId'))
            if(Product::where('id',$request->entityId)->update($upField))
                $response['status'] = 'updated';

        return json_encode($response);
    }


    /**
     * Create slug for product name
     * @param text
     * @return slug
    **/
    protected function makeSlug($text){
        $text = str_slug($text,'-');
        
        if (empty($text)) {
            $timestamp=time();
            return 'na-'.$timestamp;
        }
        if(is_numeric($text)){
            $text = 'na-'.$text;
        }
        $check=Product::where('business_id',Session::get('businessId'))->where('pro_slug',$text)->exists();
        if($check){
            $text= $text.'-'.time();
        }
        
      return $text;
    }
}
