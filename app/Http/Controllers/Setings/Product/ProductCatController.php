<?php
namespace App\Http\Controllers\Setings\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Category;


class ProductCatController extends Controller{
   /* public function index(){
        //Session::put('businessId', 300);
        $return = [];

        $cats = Category::OfBusiness()->where('cat_parent_id',0)->select('cat_id', 'cat_name', 'cat_image')->get();
        if($cats->count()){

            foreach($cats as $cat){
                $return[] = ['id'=>$cat->cat_id, 'name'=>$cat->cat_name, 'image'=>$cat->cat_image];
            }
        }

        return json_encode($return);
    }

    public function destroy($id){
        $cat = Category::find($id);
        if($cat){
            $cat->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $cat=Category::find($request->entityId); 
            $cat->cat_name=$request->text;
            if($request->image != '')
                $cat->cat_image=$request->image;
            if($cat->save())
                return $cat->cat_id;
        }
        else{
            $cat=new Category; 
            $cat->cat_business_id= Session::get('businessId');
            $cat->cat_name=$request->text;
            $cat->cat_image=$request->image;
            $cat->cat_parent_id=0;
            if($cat->save())
                return $cat->cat_id;   
        }
        return 'error';
    }*/

    public function index2(){
        
        $return = [];

        $cats = Category::OfBusiness()->select('cat_id', 'cat_name', 'cat_image','cat_parent_id','cat_sub_title','cat_slug')->get();
        if($cats->count()){
            foreach($cats as $cat){
                if($cat->cat_parent_id !=0 ){
                    $procateName = $cats->where('cat_id',$cat->cat_parent_id)->pluck('cat_name')->first();
                    /*$procat = $cats->where('cat_id',$cat->cat_parent_id)->map(function ($item) {
                        return ['name' => $item->cat_name, 'slug' => $item->cat_slug];
                    })->first();*/
                }
                else
                   $procateName = '';
                    

                $return[] = ['id'=>$cat->cat_id, 'name'=>$cat->cat_name, 'image'=>$cat->cat_image, 'procatid'=>$cat->cat_parent_id,'procatName'=>$procateName,'slug'=>$cat->cat_slug,'subtitle'=>$cat->cat_sub_title];
            }
        }
        return json_encode($return);
    }

    public function destroy2($id){
        $cat = Category::find($id);
        if($cat){
            $cat->delete();
            return $id;
        }
        return 'error';
    }

    public function save2(Request $request){
       $response['status'] = 'error'; 
        if($request->entityId != ''){
            $cat=Category::find($request->entityId); 
            $cat->cat_name=$request->text;
            if($request->old_slug == '' || strcmp($request->slug, $request->old_slug) != 0){
                if($request->slug != '')
                    $cat->cat_slug = $this->makeSlug($request->slug);
                else
                    $cat->cat_slug = $this->makeSlug($request->text); 
            }
            $cat->cat_sub_title=$request->sub_title;
            $cat->cat_parent_id = $request->procat_id;
            if($request->image != '')
                $cat->cat_image=$request->image;
            if($cat->save()){
                $response['status'] = 'success';
                $response['catId'] = $cat->cat_id;
                $response['catSlug'] = $cat->cat_slug;
            }
        }
        else{
            $cat=new Category; 
            $cat->cat_business_id= Session::get('businessId');
            $cat->cat_name=$request->text;
            if($request->slug != '')
                $cat->cat_slug = $this->makeSlug($request->slug);
            else
                $cat->cat_slug = $this->makeSlug($request->text);

            $cat->cat_sub_title=$request->sub_title;
            $cat->cat_parent_id = $request->procat_id;
            $cat->cat_image=$request->image;
            if($cat->save()){
                $response['status'] = 'success';
                $response['catId'] = $cat->cat_id;
                $response['catSlug'] = $cat->cat_slug;   
            }
        }
        return json_encode($response);
    }

    /**
     * Create slug for product category
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
        $check=Category::where('cat_business_id',Session::get('businessId'))->where('cat_slug',$text)->exists();
        if($check){
            $text= $text.'-'.time();
        }
        
      return $text;
    }

}