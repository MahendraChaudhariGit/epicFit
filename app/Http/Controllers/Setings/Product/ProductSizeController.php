<?php
namespace App\Http\Controllers\Setings\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\ProductSize;


class ProductSizeController extends Controller{

    public function index(){
        $return = [];

        $sizes = ProductSize::OfBusiness()->select('id', 'name','gender')->get();
        if($sizes->count()){
            foreach($sizes as $size){
                $return[] = ['id'=>$size->id, 'name'=>$size->name, 'gender'=>$size->gender];
            }
        }
        return json_encode($return);
    }

    public function destroy($id){
        $size = ProductSize::find($id);
        if($size){
            $size->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $size=ProductSize::find($request->entityId); 
            $size->name=$request->text;
            $size->gender=$request->gender;
            if($size->save())
                return $size->id;
        }
        else{
            $size=new ProductSize; 
            $size->business_id= Session::get('businessId');
            $size->name=$request->text;
            $size->gender=$request->gender;
            if($size->save())
                return $size->id;   
        }
        return 'error';
    }

}