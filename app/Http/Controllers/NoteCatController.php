<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\NotesCategory;
use App\ClientNote;
use Illuminate\Http\Request;
use Session;
use Auth;

class NoteCatController extends Controller{
    public function index(){
    	$return = [];

    	$cats = NotesCategory::with('notes')->OfBusiness()->get();
    	if($cats->count()){
    		foreach($cats as $cat){
                $noteVal = $cat->notes/*->where('cn_user_id',Auth::id())*/->first();
                if(count($noteVal))
                    $notesDel='yes';
                else
                    $notesDel='no';

    			$return[] = ['id'=>$cat->nc_id, 'name'=>$cat->nc_name,'noteDel'=>$notesDel];
    		}
    	}

    	return json_encode($return);
    }

    public function destroy($id){
        $responce=[];
        $responce['status']='error';
    	$cat = NotesCategory::find($id);
    	if($cat){
    		if($cat->delete()){
                $catNote=ClientNote::where('cn_type',$cat->nc_slug)->delete();
                $responce['currentId']=$id;
                $responce['slugify']=$cat->nc_slug;
                $responce['status']='success';
            }
    	}
    	return $responce;
    }

    public function save(Request $request){
        $responce=[];
        $responce['status']='error';
        if($request->entityId != ''){
            $NotesCategory=NotesCategory::find($request->entityId); 
            $NotesCategory->nc_name=$request->text;
            //$NotesCategory->nc_slug=$this->slugify($request->text);
            if($NotesCategory->save()){
                //return $NotesCategory->nc_id;
                 $responce['currentId']=$NotesCategory->nc_id;
                 $responce['slugify']=$NotesCategory->nc_slug;
                 $responce['status']='success';
            }

        }
        else{
            $NotesCategory=new NotesCategory; 
            $NotesCategory->nc_business_id= Session::get('businessId');
            $NotesCategory->nc_name=$request->text;
            $NotesCategory->nc_slug=$this->slugify($request->text);
            if($NotesCategory->save()){
                //return $NotesCategory->nc_id; 
                $responce['currentId']=$NotesCategory->nc_id;
                $responce['slugify']=$NotesCategory->nc_slug; 
                $responce['status']='success'; 
            }
        }
    	return $responce;
    }
    protected function slugify($text){
        $text = str_slug($text,'-');
        
        if (empty($text)) {
            $timestamp=time();
            return 'na-'.$timestamp;
        }
        if(is_numeric($text)){
            $text = 'na-'.$text;
        }
        $check=NotesCategory::whereIn('nc_business_id',[Session::get('businessId'),0])->where('nc_slug',$text)->exists();
        if($check){
            $text= $text.'-'.time();
        }
        
      return $text;
    }
}

    