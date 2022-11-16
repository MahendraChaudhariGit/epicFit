<?php

namespace App\Http\Controllers\Result;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Result\Models\MeasurementGalleryImage;
use App\Result\Models\MeasurementBeforeAfterImage;
use App\Result\Models\TempProgressPhoto;
use App\Result\Models\FinalProgressPhoto;
use App\Http\Requests\Progress\ProgressPhotoValidation;
use Auth;



class MeasurementController extends Controller 
{
    public function addGalleryImage(Request $request)
    {  
        $images_name_array = explode(',',$request->images_name);
        $mesaurment_gallery = new MeasurementGalleryImage();
        if($request->hasfile('images') && !empty($images_name_array))
        {
            foreach($request->file('images') as $key => $image)
            {
                $file_name = Auth::User()->account_id.time().rand(10,100).'.'.$image->extension();
                $image->move(public_path().'/result/gallery-images/', $file_name);  
                $data['image'] = $file_name;
                $data['image_name'] = $images_name_array[$key];
                $data['uploaded_by'] = Auth::User()->account_id;
                $mesaurment_gallery->create($data);
            }
        }
        if($mesaurment_gallery)
        {
            return redirect('epic/WeightAndDate/Measurements')->with('message','Gallery images uploaded successfully!!');
        }
    }  

    public function addBeforeAfter(Request $request)
    {
        if($request->before_after_id)
        {
            $mesaurment_before_after = MeasurementBeforeAfterImage::where('id',$request->before_after_id)->first();
        }
        else
        {
            $mesaurment_before_after = new MeasurementBeforeAfterImage();
        }

        if($request->before_image_capture)
        {
            $before_image = $this->getFileContent($request->before_image_capture,'capture');
        }
        if($request->after_image_capture)
        {
            $after_image = $this->getFileContent($request->after_image_capture,'capture');
        }

        if(isset($request->before_image))
        {
            $before_image = $this->getFileContent($request->before_image,'upload');
        }
        if(isset($request->after_image))
        {
            $after_image = $this->getFileContent($request->after_image,'upload');   
        }
        $mesaurment_before_after->title = $request->title;
        $mesaurment_before_after->before_image = $before_image ? $before_image : $mesaurment_before_after->before_image;
        $mesaurment_before_after->after_image = $after_image ? $after_image : $mesaurment_before_after->after_image;
        $mesaurment_before_after->uploaded_by = Auth::User()->account_id;
        $mesaurment_before_after->save();
        if($mesaurment_before_after)
        {
            return redirect('epic/WeightAndDate/Measurements')->with('message',' Record saved successfully!!');;
        }
    }

    private function getFileContent($uploaded_file,$upload_type)
    {
        if($upload_type == 'capture')
        {
            $file_name = Auth::User()->account_id.time().rand(101,200).'.'.$uploaded_file->extension();
            $uploaded_file->move(public_path().'/result/before-after-images/', $file_name);  
            
            // $folderPath = public_path().'/result/before-after-images/';
            // $image_parts = explode(";base64,",$uploaded_file);
            // $image_type_aux = explode("image/", $image_parts[0]);
            // $image_type = $image_type_aux[1];
            // $image_base64 = base64_decode($image_parts[1]);
            // $file_name = Auth::User()->account_id.time().rand(10,100).'.'.$image_type;
            // $file = $folderPath . $file_name;
            // file_put_contents($file, $image_base64);
        }
        else
        {
            $file_name = Auth::User()->account_id.time().rand(101,200).'.'.$uploaded_file->extension();
            $uploaded_file->move(public_path().'/result/before-after-images/', $file_name);  
        }
        return $file_name;
    }

    public function deleteBeforeAfter(Request $request)
    {
        $is_exist_before_after = MeasurementBeforeAfterImage::where('id',$request->before_after_id)->first();
        if($is_exist_before_after)
        {
            @unlink(public_path().'/result/before-after-images/'.$is_exist_before_after->after_image );
            @unlink(public_path().'/result/before-after-images/'.$is_exist_before_after->before_image );
            $is_exist_before_after->delete();
            return response()->json([
            'message'=>'Deleted successfully!!',
            'status'=> true
            ]);
        }
    }


    public function addProgressForm(Request $request,$client_id)
    {
        return view('Result.progress',compact('client_id'));
    }

    public function saveTempProgress(Request $request)
    {

        if(isset($request->is_exist) && $request->is_exist == 'yes')
        {
            $temp_progress = TempProgressPhoto::where(['id'=>$request->id,'client_id'=>$request->client_id])->first();
            @unlink(public_path().'/result/temp-progress-photos/'.$temp_progress->image );
        }
        elseif (isset($request->remove_uploaded_photo) && $request->remove_uploaded_photo == 'yes') 
        {
            $progress_photos = TempProgressPhoto::where(['client_id'=>$request->client_id])->get()->toArray();
            foreach ($progress_photos as $key => $value) 
            {
                $progress_photo = TempProgressPhoto::where(['client_id'=>$value['client_id']])->first();
                @unlink(public_path().'/result/temp-progress-photos/'.$value['image']);
                $progress_photo->delete();  
            }
            return response()->json([
            'message'=>'files removed successfully!!',
            'status'=> true,
            'data' => null
            ]);

        }
        elseif (isset($request->delete_preview_img) && $request->delete_preview_img == 'yes') 
        {
            $progress_photo = TempProgressPhoto::where(['id'=>$request->id,'client_id'=>$request->client_id])->first();
            @unlink(public_path().'/result/temp-progress-photos/'.$progress_photo->image);
            $progress_photo->delete();  
            $get_temp_progress = TempProgressPhoto::where('client_id',$request->client_id)->get()->toArray();
            return response()->json([
            'message'=>'files removed successfully!!',
            'status'=> true,
            'data' => $get_temp_progress
            ]);

        }
        else
        {
            $temp_progress = new TempProgressPhoto();
        }

        if($request->file('file'))
        {
            $file = $request->file('file');
            $file_name = $request->client_id.time().rand(101,200).'.'.$file->extension();
            $file->move(public_path().'/result/temp-progress-photos/', $file_name);   
        }

        $temp_progress->client_id = $request->client_id;
        $temp_progress->image = $file_name;
        $temp_progress->image_type = $request->image_type ? $request->image_type : $temp_progress->image_type ;
        $temp_progress->pose_type = $request->pose_type ? $request->pose_type : $temp_progress->pose_type;
        $temp_progress->date = $request->date ? $request->date : $temp_progress->date;
        $temp_progress->save();

        $get_temp_progress = TempProgressPhoto::where('client_id',$request->client_id)->get()->toArray();
        return response()->json([
            'message'=>'files uploaded successfully!!',
            'status'=> true,
            'data' => $get_temp_progress
            ]);
    }

    public  function checkProgressPhotoExist(Request $request)
    {
        if($request->image_type != 'other')
        {
            $is_exist_progress_photo = TempProgressPhoto::where(['client_id'=>$request->client_id,'image_type'=>$request->image_type,'pose_type'=>$request->pose_type])->first();
            if($is_exist_progress_photo)
            {
                return response()->json([
                'message'=>'Photo exist',
                'status'=> true,
                'data'=> ['pose_type'=>ucfirst($request->pose_type),'date'=>date('d-m-Y',strtotime($is_exist_progress_photo->date)),'id'=>$is_exist_progress_photo->id]
                ]);
            }
            else
            {
                return response()->json([
                'message'=>'Photo not exist',
                'status'=> false,
                ]);
            }
        }
        else
        {
            return response()->json([
            'message'=>'Photo not exist',
            'status'=> false,
            ]);
        }
    }

    public function saveProgress(ProgressPhotoValidation $request)
    {
        $temp_destination= public_path('result/temp-progress-photos');
        $orginal_destination= public_path('result/final-progress-photos');
        $temp_progress_photos = TempProgressPhoto::where('client_id',$request->client_id)->get()->toArray();
        if(!empty($temp_progress_photos))
        {
            foreach ($temp_progress_photos as $key => $value) 
            {
                $final_progress_photos = new FinalProgressPhoto();
                $temp_image = $temp_destination.'/'.$value['image'];
                rename( $temp_image,$orginal_destination.'/'.basename($value['image']) );
                $final_progress_photos->client_id = $value['client_id'];
                $final_progress_photos->title = $request->title;
                $final_progress_photos->image = $value['image'];
                $final_progress_photos->image_type = $value['image_type'];
                $final_progress_photos->pose_type = $value['pose_type'];
                $final_progress_photos->date = $value['date'];
                $final_progress_photos->save();
                TempProgressPhoto::where('id',$value['id'])->delete();
            }
            return redirect('epic/WeightAndDate/Measurements')->with('message','Record saved successfully!!');;
            
        }
    }

    public function deleteGalleryImage(Request $request)
    {
        if($request->id && $request->image)
        {
            FinalProgressPhoto::where('id',$request->id)->delete();
            @unlink(public_path().'/result/final-progress-photos/'.$request->image);
            return response()->json([
            'message'=>'Image deleted successfully !!',
            'status'=> true,
            ]);
        }
    }

    public function downloadGalleryImage(Request $request,$id)
    {
        if($id)
        {
            $final_progress  = FinalProgressPhoto::where('id',$id)->first();
            $mime = substr($final_progress->image, strpos($final_progress->image, ".") + 1);  
            $path = public_path(). '/result/final-progress-photos/'. $final_progress->image;
            return response()->download($path, Auth::User()->name.Auth::User()->last_name.'.'.$final_progress->title.'.'.$final_progress->pose_type.'.'.$mime);
        }
    }

}
