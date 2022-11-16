<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Clients;
use Illuminate\Http\Request;
use App\PostureImage;
use PDF;
use Auth;
use App\ClientMenu;

class PostureController extends Controller
{
    public function postureLists(){
        $get_menus = ClientMenu::where('client_id',Auth::user()->account_id)->first();
        if(isset($get_menus) && !in_array("posture", explode(',',$get_menus->menues))){
            return redirect('access-restricted');
        }
        
        $client_id = Auth::user()->account_id;
        $postures = PostureImage::where('client_id',$client_id)->orderBy('id','desc')->get()->toArray();
        return view('Result.posture.posture-lists',compact('postures'));
    }

    public function uploadFile(Request $request){
        if($request->hasFile('fileToUpload')) {
            $file = $request->file('fileToUpload');
            $timestamp = md5(time().rand());
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $name = $timestamp.'.'.$extension;
            $file->move(public_path().'/posture-images/', $name);
            $filename = public_path().'/posture-images/'.$name;
            $this->correctImageOrientation($filename); 
            return $name;
        }
    }
    public function correctImageOrientation($filename){
        if (function_exists('exif_read_data')) {
            $exif = exif_read_data($filename);
            if($exif && isset($exif['Orientation'])) {
              $orientation = $exif['Orientation'];
              if($orientation != 1){
                $img = imagecreatefromjpeg($filename);
                $deg = 0;
                switch ($orientation) {
                  case 3:
                    $deg = 180;
                    break;
                  case 6:
                    $deg = 270;
                    break;
                  case 8:
                    $deg = 90;
                    break;
                }
                if ($deg) {
                  $img = imagerotate($img, $deg, 0);        
                }
                imagejpeg($img, $filename, 95);
              }
            } 
        } 
    }

    public function savePostureImage(Request $request)
    { 
        // dd($request->all());
        $posture = PostureImage::where('id',$request->posture_id)->first();
        if($request->image_name == 'image1'){
            $column_name = 'image1';
            $added_from = 'added_from1';
            $data = [
                'image1' => $request->photoName,
                'xpos1' => null,
                'ypos1' => null,
                'angle1' => null,
                'front_inches' => null,
                'image_path1' => null,
            ];
        }
        if($request->image_name == 'image2'){
            $column_name = 'image2';
            $added_from = 'added_from2';
            $data = [
                'image2' => $request->photoName,
                'xpos2' => null,
                'ypos2' => null,
                'angle2' => null,
                'right_inches' => null,
                'image_path2' => null,
            ];
        }
        if($request->image_name == 'image3'){
            $column_name = 'image3';
            $added_from = 'added_from3';
            $data = [
                'image3' => $request->photoName,
                'xpos3' => null,
                'ypos3' => null,
                'angle3' => null,
                'back_inches' => null,
                'image_path3' => null,
            ];
        }
        if($request->image_name == 'image4'){
            $column_name = 'image4';
            $added_from = 'added_from4';
            $data = [
                'image4' => $request->photoName,
                'xpos4' => null,
                'ypos4' => null,
                'angle4' => null,
                'left_inches' => null,
                'image_path4' => null,
            ];
        }
        if($posture) {
            $posture->update($data);
            $data = [
                'status' => 'update',
                'msg' => "Image saved successfully",
            ];
        }else{
            $save = PostureImage::create([
                'client_id' => Auth::user()->account_id,
                'added_from' => 0,
                $column_name => $request->photoName,
                $added_from => 0,
            ]);
            $data = [
                'status' => 'create',
                'posture_id' => $save->id,
                'msg' => "Image saved successfully",
            ];
        }
       
        return response()->json($data);
    }



    public function uploadCaptureFile(Request $request){
        if($request->picfrom == 'webcamera'){
            $file =  $request->data;
            $folderPath = public_path().'/posture-images/';
    
            $image_parts = explode(";base64,", $file);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
    
            $image_base64 = base64_decode($image_parts[1]);
    
                $timestamp = md5(time().rand());
                    $fileName = $timestamp.'.'.$image_type;
    
            $file = $folderPath . $fileName;
            $this->correctImageOrientation($file); 
            file_put_contents($file, $image_base64);
            return $fileName;
        }else{
            $file = $request->file('fileToUpload');
            $timestamp = md5(time().rand());
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $name = $timestamp.'.'.$extension;
            $file->move(public_path().'/posture-images/', $name);
            $this->correctImageOrientation(public_path().'/posture-images/'.$name); 
    
            return $name;
        }
        

        
    }
}
