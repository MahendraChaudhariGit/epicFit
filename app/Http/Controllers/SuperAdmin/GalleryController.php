<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AddGalleryCategory;
use App\GalleryCategoryList;
use Auth;
use App\Clients;
use App\Staff;
use DB;

class GalleryController extends Controller
{
    public function index(){
        $categories = AddGalleryCategory::whereNull('cat_id')->get();
        return view('super-admin.gallery.index',compact('categories'));
    }

    public function categoryList(){
        $categories = AddGalleryCategory::with('gallery_category_list')->whereNull('cat_id')->get();
        return view('super-admin.gallery.category-list',compact('categories'));
    }

    public function addCategory(Request $request){
        $clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->get()->toArray();
        // dd($clients);
        return view('super-admin.gallery.create',compact('clients'));
    }

    public function saveCategory(Request $request){
        $this->validate($request, [
            'cat_name' => ['required', 'string', 'max:255'],
            // 'client_id' => ['required'],
        ]);
        // dd($request->all());
        $add_cat = AddGalleryCategory::create([
            // 'user_id' => $request->client_id,
            'cat_name' => $request->cat_name,
        ]);
        if(isset($add_cat)){
            session()->flash('msg', 'Category added successfully');
            
        }else{
            session()->flash('msg', 'Category not added! Please try again');
        }
        return redirect()->back();
    }

    public function deleteCategory($id){
        $delete_cat = AddGalleryCategory::where('id',$id)->first();
        if(isset($delete_cat)){
            $get_gallery = GalleryCategoryList::where('cat_id',$delete_cat->id);
            if(count($get_gallery->get()->toArray()) > 0){
                $get_gallery->delete();
            }
            AddGalleryCategory::where('id',$id)->delete();
            session()->flash('msg', 'Category deleted successfully');
        }else{
            session()->flash('msg', 'Category not deleted! Please try again');
        }
        return response()->json(true);
    }

    public function allImages($id){
        // $cat_id = $id;
        $get_cat = AddGalleryCategory::where('id',$id)->first();
        if(isset($get_cat)){
            $sub_categories = AddGalleryCategory::with('gallery_category_list')->where('cat_id',$get_cat->id)->get();
            $get_images = GalleryCategoryList::where('cat_id',$get_cat->id)->get();
            return view('super-admin.gallery.images',compact('get_images','get_cat','sub_categories'));
        }else{
            return redirect()->back();
        }
        
    }

    public function addImages($id){
        $cat_id = $id;
        return view('super-admin.gallery.add-images',compact('cat_id'));
    }

    public function editImages($id){
        $get_data = GalleryCategoryList::find($id);
        $cat_id = $get_data->cat_id;
        return view('super-admin.gallery.add-images',compact('get_data','cat_id'));
    }

    public function saveImages(Request $request){
        if(count($request->file('cat_image')) > 0){
            foreach($request->file('cat_image') as $key => $file)
            {
                if($request->hasfile('cat_image')){
                    $name = $key.md5(uniqid(rand(), true)).'.'.$file->extension();
                    $file->move('category-images/', $name);  
                    $image = $name;  
                
                    GalleryCategoryList::create([
                        'cat_id' => $request->cat_id,
                        'cat_image' => $image,
                        'description' => $request->image_desc[0],
                    ]);
                }
            }
            
            session()->flash('msg', 'Images added successfully.');
            return redirect("epic-super-admin/images/".$request->cat_id);
        }else{
            session()->flash('msg', 'No image added.');
            return redirect("epic-super-admin/images/".$request->cat_id);
        }
    }

    public function updateImage(Request $request){
        $get_data = GalleryCategoryList::where('id',$request->id)->where('cat_id',$request->cat_id)->first();
        
        if($file = $request->file('cat_image')){
            if($request->hasfile('cat_image')){
                $name = md5(uniqid(rand(), true)).'.'.$file->extension();
                $file->move('category-images/', $name);  
                $image = $name; 
            }
        }else{
            $image = $get_data->cat_image;
        }
        $data = [
            'cat_id' => $request->cat_id,
            'cat_image' => $image,
            'description' => $request->image_desc,
        ];
        $get_data->update($data);
        // dd($data);
        session()->flash('msg', 'Images updated successfully.');
        return redirect("epic-super-admin/images/".$request->cat_id);
    }

    public function deleteImage($id){
        $delete_img = GalleryCategoryList::where('id',$id)->delete();
        session()->flash('msg', 'Images deleted successfully.');
        return response()->json(true);
    }

    public function deleteImages(Request $request){
        $delete_img = GalleryCategoryList::whereIn('id',$request->ids)->delete();
        session()->flash('msg', 'Images deleted successfully.');
        return response()->json(true);
    }

    public function saveSubCategory(Request $request){
        // dd($request->all());
        $this->validate($request, [
            'cat_name' => ['required', 'string', 'max:255'],
        ]);
        if($request->data_type == 'add'){
            $add_cat = AddGalleryCategory::create([
                'cat_id' => $request->cat_id,
                'user_id' => $request->user_id,
                'cat_name' => $request->cat_name,
            ]);
            if(isset($add_cat)){
                session()->flash('msg', 'Sub Category added successfully');
                
            }else{
                session()->flash('msg', 'Sub Category not added! Please try again');
            }
            // return redirect('epic-super-admin/images/'.$request->cat_id);
            return redirect()->back();
        }else{
            $update = AddGalleryCategory::find($request->cat_id);
            $edit_cat = $update->update([
                'cat_name' => $request->cat_name,
            ]);
            if(isset($edit_cat)){
                session()->flash('msg', 'Sub Category edited successfully');
                
            }else{
                session()->flash('msg', 'Sub Category not edited! Please try again');
            }
            return redirect()->back();
        }
        
    }

    public function goBack($id){
       
        $sub_categories = AddGalleryCategory::where('id',$id)->first();
        if(!empty($sub_categories->cat_id)){
            return response()->json(url('epic-super-admin/images/'.$sub_categories->cat_id));
        }else{
            return response()->json(url('epic-super-admin/category/list'));
        }
    }
}

