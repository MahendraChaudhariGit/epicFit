<?php

namespace App\Http\Controllers;

use App\Clients;
use App\Parq;
use App\PersonalMeasurement;
use Illuminate\Http\Request;
use App\PostureImage;
use Carbon\Carbon;
use PDF;
use Auth;

class PostureController extends Controller
{
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
            
        }else if($request->photoName){
            $iWidth = $request->w;
            $iHeight = $request->h;
            $uploadPath = public_path().'/posture-images/';
            $temp = explode('.', $request->photoName);
            $extension = $temp[1];
            $extension = strtolower($extension);
            
            if($extension == 'jpg' || $extension == 'jpeg')
                $vImg = @imagecreatefromjpeg($uploadPath.$request->photoName);
            else if($extension == 'png')
                $vImg = @imagecreatefrompng($uploadPath.$request->photoName);
            else
                @unlink($uploadPath.$request->photoName);
                        
            $vDstImg = @imagecreatetruecolor($iWidth, $iHeight);
            if($request->widthScale && $request->widthScale != 'Infinity'){
                $x1 = (int)($request->x1*$request->widthScale);
                $w = (int)($request->w*$request->widthScale);
            }
            else{
                $x1 = (int)$request->x1;
                $w = (int)$request->w;
            }
            if($request->heightScale && $request->heightScale != 'Infinity'){
                $y1 = (int)($request->y1*$request->heightScale);
                $h = (int)($request->h*$request->heightScale);
            }
            else{
                $y1 = (int)$request->y1;
                $h = (int)$request->h;
            }
                
            imagecopyresampled($vDstImg, $vImg, 0, 0, $x1, $y1, $iWidth, $iHeight, $w, $h);
            imagejpeg($vDstImg, $uploadPath.'thumb_'.$request->photoName, 90);
            if($request->prePhotoName){
                @unlink($uploadPath.$request->prePhotoName);
                @unlink($uploadPath.'thumb_'.$request->prePhotoName);
            }

            return $request->photoName;
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
                'added_from1' => 1
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
                'added_from2' => 1
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
                'added_from3' => 1
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
                'added_from4' => 1
            ];
        }
        if($posture) {
            $posture->update($data);
            $data = [
                'status' => 'update',
                'posture_id' => $posture->id,
                'image_type' => $column_name,
                
            ];
        }else{
            $save = PostureImage::create([
                'client_id' => $request->client_id,
                'added_from' => 1,
                $column_name => $request->photoName,
                $added_from => 1,
            ]);
            $data = [
                'status' => 'create',
                'posture_id' => $save->id,
                'image_type' => $column_name,
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

    public function screenshot($file){
        $folderPath = public_path().'/uploads/';

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
    }

    public function postureAnalysis(Request $request){
        $getimage = PostureImage::where('id',$request->posture_id)->first();
        $fileName ='';
        if($request->image == 'front'){
            $old_data1 = explode(',',$getimage->front_inches);
            $new_data1 = $request->inch_data;
            if(empty($getimage->front_inches)){
                if(($new_data1[0]!="0" || $new_data1[1]!="0" || $new_data1[2]!="0" || $new_data1[3]!="0")){
                $inches1 = implode(',',$request->inch_data);
                $getimage->update(['front_inches'=>$inches1]);
                }
            }
            else{
                $store_data1[0] = ($new_data1[0]!='NaN' && $new_data1[0]!="0") ? $new_data1[0] : $old_data1[0];
                $store_data1[1] = ($new_data1[1]!='NaN' && $new_data1[1]!="0") ? $new_data1[1] : $old_data1[1];
                $store_data1[2] = ($new_data1[2]!='NaN' && $new_data1[2]!="0") ? $new_data1[2] : $old_data1[2];
                $store_data1[3] = ($new_data1[3]!='NaN' && $new_data1[3]!="0") ? $new_data1[3] : $old_data1[3];
                $inches1 = implode(',',$store_data1);
                $getimage->update(['front_inches'=>$inches1]);
            }
            if(($new_data1[0]!="0" || $new_data1[1]!="0" || $new_data1[2]!="0" || $new_data1[3]!="0")){
                if(empty($getimage->image_path1)){
                    if($request->has('data_url')){
                        $file1 =  $request->data_url;
                        $fileName1 = $this->screenshot($file1);
                    }
                    $getimage->update(['image_path1' =>$fileName1]);
                }
            }
            
        }
        if($request->image == 'right'){
            $old_data2 = explode(',',$getimage->right_inches);
            $new_data2 = $request->inch_data;
            if(empty($getimage->right_inches)){
                if(($new_data2[0]!="0" || $new_data2[1]!="0" || $new_data2[2]!="0" || $new_data2[3]!="0")){
                $inches2 = implode(',',$request->inch_data);
                $getimage->update(['right_inches'=>$inches2]);
                }
            }
            else{
                $store_data2[0] = ($new_data2[0]!='NaN' && $new_data2[0]!="0") ? $new_data2[0] : $old_data2[0];
                $store_data2[1] = ($new_data2[1]!='NaN' && $new_data2[1]!="0") ? $new_data2[1] : $old_data2[1];
                $store_data2[2] = ($new_data2[2]!='NaN' && $new_data2[2]!="0") ? $new_data2[2] : $old_data2[2];
                $store_data2[3] = ($new_data2[3]!='NaN' && $new_data2[3]!="0") ? $new_data2[3] : $old_data2[3];
                $store_data2[4] = ($new_data2[4]!='NaN' && $new_data2[4]!="0") ? $new_data2[4] : $old_data2[4];
                $store_data2[5] = ($new_data2[5]!='NaN' && $new_data2[5]!="0") ? $new_data2[5] : $old_data2[5];

                $inches2 = implode(',',$store_data2);
                $getimage->update(['right_inches'=>$inches2]);
            }
            if(($new_data2[0]!="0" || $new_data2[1]!="0" || $new_data2[2]!="0" || $new_data2[3]!="0")){
                if(empty($getimage->image_path2)){
                    if($request->has('data_url')){
                        $file2 =  $request->data_url;
                        $fileName2 = $this->screenshot($file2);
                    }
                    $getimage->update(['image_path2' =>$fileName2]);
                }
            }

        }
        if($request->image == 'back'){
            $old_data3 = explode(',',$getimage->back_inches);
            $new_data3 = $request->inch_data;
            if(empty($getimage->back_inches)){
                if(($new_data3[0]!="0" || $new_data3[1]!="0" || $new_data3[2]!="0" || $new_data3[3]!="0")){
                $inches3 = implode(',',$request->inch_data);
                $getimage->update(['back_inches'=>$inches3]);
                }
            }
            else{
                $store_data3[0] = ($new_data3[0]!='NaN' && $new_data3[0]!="0") ? $new_data3[0] : $old_data3[0];
                $store_data3[1] = ($new_data3[1]!='NaN' && $new_data3[1]!="0") ? $new_data3[1] : $old_data3[1];
                $store_data3[2] = ($new_data3[2]!='NaN' && $new_data3[2]!="0") ? $new_data3[2] : $old_data3[2];
                $store_data3[3] = ($new_data3[3]!='NaN' && $new_data3[3]!="0") ? $new_data3[3] : $old_data3[3];

                $inches3 = implode(',',$store_data3);
                $getimage->update(['back_inches'=>$inches3]);
            }
            if(($new_data3[0]!="0" || $new_data3[1]!="0" || $new_data3[2]!="0" || $new_data3[3]!="0")){
                if(empty($getimage->image_path3)){
                    if($request->has('data_url')){
                        $file3 =  $request->data_url;
                        $fileName3 = $this->screenshot($file3);
                    }
                    $getimage->update(['image_path3' =>$fileName3]);
                }
            }
        }
        if($request->image == 'left'){
            $old_data4 = explode(',',$getimage->left_inches);
            $new_data4 = $request->inch_data;
            if(empty($getimage->left_inches)){
                if(($new_data4[0]!="0" || $new_data4[1]!="0" || $new_data4[2]!="0" || $new_data4[3]!="0")){
                $inches4 = implode(',',$request->inch_data);
                $getimage->update(['left_inches'=>$inches4]);
                }
            }
            else{
                $store_data4[0] = ($new_data4[0]!='NaN' && $new_data4[0]!="0") ? $new_data4[0] : $old_data4[0];
                $store_data4[1] = ($new_data4[1]!='NaN' && $new_data4[1]!="0") ? $new_data4[1] : $old_data4[1];
                $store_data4[2] = ($new_data4[2]!='NaN' && $new_data4[2]!="0") ? $new_data4[2] : $old_data4[2];
                $store_data4[3] = ($new_data4[3]!='NaN' && $new_data4[3]!="0") ? $new_data4[3] : $old_data4[3];
                $store_data4[4] = ($new_data4[4]!='NaN' && $new_data4[4]!="0") ? $new_data4[4] : $old_data4[4];
                $store_data4[5] = ($new_data4[5]!='NaN' && $new_data4[5]!="0") ? $new_data4[5] : $old_data4[5];

                $inches4 = implode(',',$store_data4);
                $getimage->update(['left_inches'=>$inches4]);
            }
            if(($new_data4[0]!="0" || $new_data4[1]!="0" || $new_data4[2]!="0" || $new_data4[3]!="0")){
                if(empty($getimage->image_path4)){
                    if($request->has('data_url')){
                        $file4 =  $request->data_url;
                        $fileName4 = $this->screenshot($file4);
                    }
                    $getimage->update(['image_path4' =>$fileName4]);
                }
            }

        }
        if($request->image_name == 'image1'){
            if(!empty($getimage->image1)){
                if(!empty($getimage->image2)){
                    $next_image = 'image2';
                }elseif(!empty($getimage->image3)){
                    $next_image = 'image3';
                }elseif(!empty($getimage->image4)){
                    $next_image = 'image4';
                }else{
                    $next_image = 'last_image';
                }
                $notification = [
                    'status' => true,
                    'image_name' => $getimage->image1,
                    'client_id' => $request->client_id,
                    'posture_id' => $getimage->id,
                    'xPos' => $getimage->xpos1 ? $getimage->xpos1 : '',
                    'yPos' => $getimage->ypos1 ? $getimage->ypos1 : '',
                    'image_path' => $getimage->image_path1,
                    'previous_image' => '',
                    'current_image' => 'image1',
                    'next_image' => $next_image,
                    'view' => 'front'
                ];
            }else{
                $notification = [
                    'status' => false,
                    'posture_data' => $getimage->toArray()
                ];
            }
        }
        elseif($request->image_name == 'image2'){
            if(!empty($getimage->image2)){
                if(!empty($getimage->image1)){
                    $previous_image = 'image1';
                }
                if(!empty($getimage->image3)){
                    $next_image = 'image3';
                }elseif(!empty($getimage->image4)){
                    $next_image = 'image4';
                }else{
                    $next_image = 'last_image';
                }
                $notification = [
                    'status' => true,
                    'image_name' => $getimage->image2,
                    'client_id' => $request->client_id,
                    'posture_id' => $getimage->id,
                    'xPos' => $getimage->xpos2 ? $getimage->xpos2 : '',
                    'yPos' => $getimage->ypos2 ? $getimage->ypos2 : '',
                    'image_path' => $getimage->image_path2,
                    'previous_image' => $previous_image,
                    'current_image' => 'image2',
                    'next_image' => $next_image,
                    'view' => 'right'
                ];
            }else{
                $notification = [
                    'status' => false,
                    'posture_data' => $getimage->toArray()
                ];
            }
        }
        elseif($request->image_name == 'image3'){
            if(!empty($getimage->image3)){
                if(!empty($getimage->image2)){
                    $previous_image = 'image2';
                }elseif(!empty($getimage->image1)){
                    $previous_image = 'image1';
                }
                if(!empty($getimage->image4)){
                    $next_image = 'image4';
                }else{
                    $next_image = 'last_image';
                }
                $notification = [
                    'status' => true,
                    'image_name' => $getimage->image3,
                    'client_id' => $request->client_id,
                    'posture_id' => $getimage->id,
                    'xPos' => $getimage->xpos3 ? $getimage->xpos3 : '',
                    'yPos' => $getimage->ypos3 ? $getimage->ypos3 : '',
                    'image_path' => $getimage->image_path3,
                    'previous_image' => $previous_image,
                    'current_image' => 'image3',
                    'next_image' => $next_image,
                    'view' => 'back'
                ];
            }else{
                $notification = [
                    'status' => false,
                    'posture_data' => $getimage->toArray()
                ];
            }
        }
        elseif($request->image_name == 'image4'){
            if(!empty($getimage->image3)){
                $previous_image = 'image3';
            }elseif(!empty($getimage->image2)){
                $previous_image = 'image2';
            }elseif(!empty($getimage->image1)){
                $previous_image = 'image1';
            }
            if(!empty($getimage->image4)){
                $notification = [
                    'status' => true,
                    'image_name' => $getimage->image4,
                    'client_id' => $request->client_id,
                    'posture_id' => $getimage->id,
                    'xPos' => $getimage->xpos4 ? $getimage->xpos4 : '',
                    'yPos' => $getimage->ypos4 ? $getimage->ypos4 : '',
                    'image_path' => $getimage->image_path4,
                    'previous_image' => $previous_image,
                    'current_image' => 'image4',
                    'next_image' => 'last_image',
                    'view' => 'left'
                ];
            }else{
                $notification = [
                    'status' => false,
                    'posture_data' => $getimage->toArray()
                ];
            }
        }
        else{
            if(!empty($getimage->image1)){
                if(!empty($getimage->image2)){
                    $next_image = 'image2';
                }elseif(!empty($getimage->image3)){
                    $next_image = 'image3';
                }elseif(!empty($getimage->image4)){
                    $next_image = 'image4';
                }else{
                    $next_image = 'last_image';
                }
                $notification = [
                    'status' => true,
                    'image_name' => $getimage->image1,
                    'client_id' => $request->client_id,
                    'posture_id' => $getimage->id,
                    'xPos' => $getimage->xpos1 ? $getimage->xpos1 : '',
                    'yPos' => $getimage->ypos1 ? $getimage->ypos1 : '',
                    'image_path' => $getimage->image_path1,
                    'previous_image' => '',
                    'current_image' => 'image1',
                    'next_image' => $next_image,
                    'view' => 'front'
                ];
            }elseif(!empty($getimage->image2)){
                if(!empty($getimage->image1)){
                    $previous_image = 'image1';
                }
                if(!empty($getimage->image3)){
                    $next_image = 'image3';
                }elseif(!empty($getimage->image4)){
                    $next_image = 'image4';
                }else{
                    $next_image = 'last_image';
                }
                $notification = [
                    'status' => true,
                    'image_name' => $getimage->image2,
                    'client_id' => $request->client_id,
                    'posture_id' => $getimage->id,
                    'xPos' => $getimage->xpos2 ? $getimage->xpos2 : '',
                    'yPos' => $getimage->ypos2 ? $getimage->ypos2 : '',
                    'image_path' => $getimage->image_path2,
                    'previous_image' => $previous_image,
                    'current_image' => 'image2',
                    'next_image' => $next_image,
                    'view' => 'right'
                ];
            }elseif(!empty($getimage->image3)){
                if(!empty($getimage->image2)){
                    $previous_image = 'image2';
                }elseif(!empty($getimage->image1)){
                    $previous_image = 'image1';
                }
                if(!empty($getimage->image4)){
                    $next_image = 'image4';
                }else{
                    $next_image = 'last_image';
                }
                $notification = [
                    'status' => true,
                    'image_name' => $getimage->image3,
                    'client_id' => $request->client_id,
                    'posture_id' => $getimage->id,
                    'xPos' => $getimage->xpos3 ? $getimage->xpos3 : '',
                    'yPos' => $getimage->ypos3 ? $getimage->ypos3 : '',
                    'image_path' => $getimage->image_path3,
                    'previous_image' => $previous_image,
                    'current_image' => 'image3',
                    'next_image' => $next_image,
                    'view' => 'back'
                ];
            }elseif(!empty($getimage->image4)){
                if(!empty($getimage->image3)){
                    $previous_image = 'image3';
                }elseif(!empty($getimage->image2)){
                    $previous_image = 'image2';
                }elseif(!empty($getimage->image1)){
                    $previous_image = 'image1';
                }
                $notification = [
                    'status' => true,
                    'image_name' => $getimage->image4,
                    'client_id' => $request->client_id,
                    'posture_id' => $getimage->id,
                    'xPos' => $getimage->xpos4 ? $getimage->xpos4 : '',
                    'yPos' => $getimage->ypos4 ? $getimage->ypos4 : '',
                    'image_path' => $getimage->image_path4,
                    'previous_image' => $previous_image,
                    'current_image' => 'image4',
                    'next_image' => 'last_image',
                    'view' => 'left'
                ];
            }
            else{
                $notification = [
                    'status' => false,
                    'posture_data' => $getimage->toArray()
                ];
            }
        }
        if($request->image_name == 'last_image'){
            return $this->preview($getimage);
            // $notification = [
            //     'status' => false,
            //     'posture_data' => $getimage->toArray()
            // ];
        }

        return response()->json($notification);
    }

    public function storeCoordinates(Request $request){
        // dd($request->all());
        $getData = PostureImage::where('id',$request->posture_id)->first();
        if(isset($getData) && !empty($getData->image1) && $request->image_type == 'image1'){
            $xPos = !empty($getData->xpos1) ? $getData->xpos1.','.$request->xPos : $request->xPos;
            $yPos = !empty($getData->ypos1) ? $getData->ypos1.','.$request->yPos : $request->yPos;
            $getData->update(['xpos1'=>$xPos,'ypos1'=>$yPos]);
        }
        if(isset($getData) && !empty($getData->image2) && $request->image_type == 'image2'){
            $xPos = !empty($getData->xpos2) ? $getData->xpos2.','.$request->xPos : $request->xPos;
            $yPos = !empty($getData->ypos2) ? $getData->ypos2.','.$request->yPos : $request->yPos;
            $getData->update(['xpos2'=>$xPos,'ypos2'=>$yPos]);
        }
        if(isset($getData) && !empty($getData->image3) && $request->image_type == 'image3'){
            $xPos = !empty($getData->xpos3) ? $getData->xpos3.','.$request->xPos : $request->xPos;
            $yPos = !empty($getData->ypos3) ? $getData->ypos3.','.$request->yPos : $request->yPos;
            $getData->update(['xpos3'=>$xPos,'ypos3'=>$yPos]);
        }
        if(isset($getData) && !empty($getData->image4) && $request->image_type == 'image4'){
            $xPos = !empty($getData->xpos4) ? $getData->xpos4.','.$request->xPos : $request->xPos;
            $yPos = !empty($getData->ypos4) ? $getData->ypos4.','.$request->yPos : $request->yPos;
            $getData->update(['xpos4'=>$xPos,'ypos4'=>$yPos]);
        }
        // return response()->json([
        //     'xpos' => $request->xPos,
        //     'ypos' => $request->yPos,
        // ]);
    }

    public function storeAngles(Request $request){
        // dd($request->all());
        $getData = PostureImage::where('id',$request->posture_id)->first();
        if(isset($getData) && !empty($getData->image1) && $request->image_type == 'image1'){
            $angle = ($getData->angle1 != null) ? $getData->angle1.','.$request->angle : $request->angle;
            $getData->update(['angle1'=>$angle]);
        }
        if(isset($getData) && !empty($getData->image2) && $request->image_type == 'image2'){
            $angle = ($getData->angle2 != null) ? $getData->angle2.','.$request->angle : $request->angle;
            $getData->update(['angle2'=>$angle]);
        }
        if(isset($getData) && !empty($getData->image3) && $request->image_type == 'image3'){
            $angle = ($getData->angle3 != null) ? $getData->angle3.','.$request->angle : $request->angle;
            $getData->update(['angle3'=>$angle]);
        }
        if(isset($getData) && !empty($getData->image4) && $request->image_type == 'image4'){
            $angle = ($getData->angle4 != null) ? $getData->angle4.','.$request->angle : $request->angle;
            $getData->update(['angle4'=>$angle]);
        }
    }

    public function resetAnalysis(Request $request)
    { 
        $client = PostureImage::where('id',$request->posture_id)->first();
        if($request->image_name == 'image1'){
            $data = [
                'xpos1' => null,
                'ypos1' => null,
                'angle1' => null,
                'front_inches' => null,
                'image_path1' => null
            ];
        }
        if($request->image_name == 'image2'){
            $data = [
                'xpos2' => null,
                'ypos2' => null,
                'angle2' => null,
                'right_inches' => null,
                'image_path2' => null
            ];
        }
        if($request->image_name == 'image3'){
            $data = [
                'xpos3' => null,
                'ypos3' => null,
                'angle3' => null,
                'back_inches' => null,
                'image_path3' => null
            ];
        }
        if($request->image_name == 'image4'){
            $data = [
                'xpos4' => null,
                'ypos4' => null,
                'angle4' => null,
                'left_inches' => null,
                'image_path4' => null
            ];
        }
        if ($client) {
            $client->update($data);
        }
        return redirect()->back();
    }

    public function undoAnalysis(Request $request){
        $getData = PostureImage::where('id',$request->posture_id)->first();
        if(isset($getData) && !empty($getData->image1) && $request->image_type == 'image1'){
            $xpos1 = explode(',',$getData->xpos1);
            $ypos1 = explode(',',$getData->ypos1);
            if(count($xpos1)%2 == 0 && count($ypos1)%2 == 0){
                $angle = (explode(',',$getData->angle1));
                array_pop($angle);
                $angle1 = implode(',',$angle);
            }else{
                $angle1 = $getData->angle1;
            }
            array_pop($xpos1);
            array_pop($ypos1);
            // if (($key = array_search($request->xPos, $xpos1)) !== false) {
            //     unset($xpos1[$key]);
            // }
            // if (($key = array_search($request->yPos, $ypos1)) !== false) {
            //     unset($ypos1[$key]);
            // }
            $getData->update(['xpos1'=>implode(',',$xpos1) ? implode(',',$xpos1) : null,'ypos1'=>implode(',',$ypos1) ? implode(',',$ypos1) : null, 'angle1'=>$angle1 ? $angle1 : null]);
            $data = [
                // 'xpos' => end($xpos1) ? end($xpos1) : null,
                // 'ypos' => end($ypos1) ? end($ypos1) : null,
                'xPos' => implode(',',$xpos1),
                'yPos' => implode(',',$ypos1)
            ];
        }
        if(isset($getData) && !empty($getData->image2) && $request->image_type == 'image2'){
            $xpos2 = explode(',',$getData->xpos2);
            $ypos2 = explode(',',$getData->ypos2);
            if(count($xpos2)%2 == 0 && count($ypos2)%2 == 0){
                $angle = (explode(',',$getData->angle2));
                array_pop($angle);
                $angle2 = implode(',',$angle);
            }else{
                $angle2 = $getData->angle2;
            }
            array_pop($xpos2);
            array_pop($ypos2);
            // if (($key = array_search($request->xPos, $xpos2)) !== false) {
            //     unset($xpos2[$key]);
            // }
            // if (($key = array_search($request->yPos, $ypos2)) !== false) {
            //     unset($ypos2[$key]);
            // }
            $getData->update(['xpos2'=>implode(',',$xpos2) ? implode(',',$xpos2) : null,'ypos2'=>implode(',',$ypos2) ? implode(',',$ypos2) : null, 'angle2'=>$angle2 ? $angle2 : null]);
            $data = [
                // 'xpos' => end($xpos2) ? end($xpos2) : null,
                // 'ypos' => end($ypos2) ? end($ypos2) : null,
                'xPos' => implode(',',$xpos2),
                'yPos' => implode(',',$ypos2)
            ];
        }
        if(isset($getData) && !empty($getData->image3) && $request->image_type == 'image3'){
            $xpos3 = explode(',',$getData->xpos3);
            $ypos3 = explode(',',$getData->ypos3);
            if(count($xpos3)%2 == 0 && count($ypos3)%2 == 0){
                $angle = (explode(',',$getData->angle3));
                array_pop($angle);
                $angle3 = implode(',',$angle);
            }else{
                $angle3 = $getData->angle3;
            }
            array_pop($xpos3);
            array_pop($ypos3);
            // if (($key = array_search($request->xPos, $xpos3)) !== false) {
            //     unset($xpos3[$key]);
            // }
            // if (($key = array_search($request->yPos, $ypos3)) !== false) {
            //     unset($ypos3[$key]);
            // }
            $getData->update(['xpos3'=>implode(',',$xpos3) ? implode(',',$xpos3) : null,'ypos3'=>implode(',',$ypos3) ? implode(',',$ypos3) : null, 'angle3'=>$angle3 ? $angle3 : null]);
            $data = [
                // 'xpos' => end($xpos3) ? end($xpos3) : null,
                // 'ypos' => end($ypos3) ? end($ypos3) : null,
                'xPos' => implode(',',$xpos3),
                'yPos' => implode(',',$ypos3)
            ];
        }
        if(isset($getData) && !empty($getData->image4) && $request->image_type == 'image4'){
            $xpos4 = explode(',',$getData->xpos4);
            $ypos4 = explode(',',$getData->ypos4);
            if(count($xpos4)%2 == 0 && count($ypos4)%2 == 0){
                $angle = (explode(',',$getData->angle4));
                array_pop($angle);
                $angle4 = implode(',',$angle);
            }else{
                $angle4 = $getData->angle4;
            }
            array_pop($xpos4);
            array_pop($ypos4);
            // if (($key = array_search($request->xPos, $xpos4)) !== false) {
            //     unset($xpos4[$key]);
            // }
            // if (($key = array_search($request->yPos, $ypos4)) !== false) {
            //     unset($ypos4[$key]);
            // }
            $getData->update(['xpos4'=>implode(',',$xpos4) ? implode(',',$xpos4) : null,'ypos4'=>implode(',',$ypos4) ? implode(',',$ypos4) : null, 'angle4'=>$angle4 ? $angle4 : null]);
            $data = [
                // 'xpos' => end($xpos4) ? end($xpos4) : null,
                // 'ypos' => end($ypos4) ? end($ypos4) : null,
                'xPos' => implode(',',$xpos4),
                'yPos' => implode(',',$ypos4)
            ];
        }
        return response()->json($data);
    }

    public function generatePdf($id, $mail=0){
        $posture  = PostureImage::where('id',$id)->first();
        $client = Clients::select('id','unit')->where('id',$posture->client_id)->first();
        $postureData = [];
        $angle1 = explode(',', $posture->angle1);
        $inches1 =  explode(',', $posture->front_inches);
        $angle3 = explode(',', $posture->angle3);
        $inches3 =  explode(',', $posture->back_inches);


        $rightInches2 =  explode(',', $posture->right_inches);
        $leftInches2 =  explode(',', $posture->left_inches);

        if($angle1[0] > 0){
            $headPosition ='Right';
        }else{
            $headPosition ='Left';
        }
        if($angle1[2] > 0){
            $shoulderPosition ='Right';
        }else{
            $shoulderPosition ='Left';
        }
        if($angle1[3] > 0){
            $ribcagePosition ='Right';
        }else{
            $ribcagePosition ='Left';
        }
        if($angle1[5] > 0){
            $hipsPosition ='Right';
        }else{
            $hipsPosition ='Left';
        }

        if($inches1[0] > 0){
            $inchesheadPosition ='Right';
        }else{
            $inchesheadPosition ='Left';
        }
        if($inches1[2] > 0){
            $inchesshoulderPosition ='Right';
        }else{
            $inchesshoulderPosition ='Left';
        }
        if($inches1[3] > 0){
            $inchesribcagePosition ='Right';
        }else{
            $inchesribcagePosition ='Left';
        }

        if($inches1[5] > 0){
            $incheshipsPosition ='Right';
        }else{
            $incheshipsPosition ='Left';
        }

      
        $postureData['image1_data'] = [
            'headPosition' =>$headPosition,
            'headAngle' => number_format(abs($angle1[0]),2),
            'shoulderPosition' =>  $shoulderPosition,
            'shoulderAngle' =>  number_format(abs($angle1[2]),2),
            'ribcagePosition' =>  $ribcagePosition,
            'ribcageAngle' =>  number_format(abs($angle1[3]),2),
            'hipsPosition' =>  $hipsPosition,
            'hipsAngle' =>  number_format(abs($angle1[5]),2),
            'inchesheadPosition' =>$inchesheadPosition,
            'inchesheadAngle' =>  $client->unit == 'Imperial' ? number_format(abs($inches1[0]),2) : number_format(abs($inches1[0] * 2.54),2),
            'inchesshoulderPosition' =>  $inchesshoulderPosition,
            'inchesshoulderAngle' => $client->unit == 'Imperial' ? number_format(abs($inches1[2]),2) : number_format(abs($inches1[2] * 2.54),2),
            'inchesribcagePosition' =>  $inchesribcagePosition,
            'inchesribcageAngle' => $client->unit == 'Imperial' ? number_format(abs($inches1[3]),2) : number_format(abs($inches1[3] * 2.54),2),
            'incheshipsPosition' =>  $incheshipsPosition,
            'incheshipsAngle' => $client->unit == 'Imperial' ? number_format(abs($inches1[5]),2) : number_format(abs($inches1[5] * 2.54),2),
            'image_path1' => $posture->image_path1
        ];
        if($rightInches2[0] > 0){
            $headPosition ='backward';
        }else{
            $headPosition ='forward';
        }
        if($rightInches2[1] > 0){
            $shoulderPosition ='backward';
        }else{
            $shoulderPosition ='forward';
        }
        if($rightInches2[2] > 0){
            $hipsPosition ='backward';
        }else{
            $hipsPosition ='forward';
        }
        if($rightInches2[3] > 0){
            $kneePosition ='backward';
        }else{
            $kneePosition ='forward';
        }
     
        if($rightInches2[4] > 0){
            $kneePosition ='backward';
        }else{
            $kneePosition ='forward';
        }
     
      
     
        $postureData['image2_data'] = [
            'headPosition' =>$headPosition,
            'shoulderPosition' =>$shoulderPosition,
            'hipsPosition' =>$hipsPosition,
            'kneePosition' =>$kneePosition,
            'inchesheadPosition' =>$inchesheadPosition,
            'inchesheadAngle' => $client->unit == 'Imperial' ? number_format(abs($rightInches2[0]),2) : number_format(abs($rightInches2[0] * 2.54),2),
            'inchesshoulderAngle' => $client->unit == 'Imperial' ? number_format(abs($rightInches2[1]),2) : number_format(abs($rightInches2[1] * 2.54),2),
            'inchesHipsAngle' =>$client->unit == 'Imperial' ? number_format(abs($rightInches2[2]),2) : number_format(abs($rightInches2[2] * 2.54),2),
            'incheskneeAngle' =>$client->unit == 'Imperial' ? number_format(abs($rightInches2[3]),2) : number_format(abs($rightInches2[3] * 2.54),2),
            'headWeight1' => $client->unit == 'Imperial' ? number_format(abs($rightInches2[4]),2) : number_format(abs($rightInches2[4]*0.453592),2),
            'headWeight2' => $client->unit == 'Imperial' ? number_format(abs($rightInches2[5]),2) : number_format(abs($rightInches2[5]*0.453592),2),
            // 'headWeight1' => number_format(abs($rightInches2[4]),2),
            // 'headWeight2' => number_format(abs($rightInches2[5]),2),
            'image_path2' => $posture->image_path2
        ];
        if($angle3[0] > 0){
            $headPosition ='Right';
        }else{
            $headPosition ='Left';
        }
        if($angle3[2] > 0){
            $shoulderPosition ='Right';
        }else{
            $shoulderPosition ='Left';
        }
        if($angle3[3] > 0){
            $ribcagePosition ='Right';
        }else{
            $ribcagePosition ='Left';
        }
        if($angle3[5] > 0){
            $hipsPosition ='Right';
        }else{
            $hipsPosition ='Left';
        }

        if($inches3[0] > 0){
            $inchesheadPosition ='Right';
        }else{
            $inchesheadPosition ='Left';
        }
        if($inches3[2] > 0){
            $inchesshoulderPosition ='Right';
        }else{
            $inchesshoulderPosition ='Left';
        }
        if($inches3[3] > 0){
            $inchesribcagePosition ='Right';
        }else{
            $inchesribcagePosition ='Left';
        }

        if($inches3[5] > 0){
            $incheshipsPosition ='Right';
        }else{
            $incheshipsPosition ='Left';
        }

        $postureData['image3_data'] = [
            $angle3 = explode(',', $posture->angle3),
            'headPosition' =>$headPosition,
            'headAngle' => number_format(abs($angle3[0]),2),
            'shoulderPosition' =>  $shoulderPosition,
            'shoulderAngle' =>  number_format(abs($angle3[2]),2),
            'ribcagePosition' =>  $ribcagePosition,
            'ribcageAngle' =>  number_format(abs($angle3[3]),2),
            'hipsPosition' =>  $hipsPosition,
            'hipsAngle' =>  number_format(abs($angle3[5]),2),
            'inchesheadPosition' =>$inchesheadPosition,
            'inchesheadAngle' =>  $client->unit == 'Imperial' ? number_format(abs($inches3[0]),2) : number_format(abs($inches3[0] * 2.54),2),
            'inchesshoulderPosition' =>  $inchesshoulderPosition,
            'inchesshoulderAngle' => $client->unit == 'Imperial' ? number_format(abs($inches3[2]),2) : number_format(abs($inches3[2] * 2.54),2),
            'inchesribcagePosition' =>  $inchesribcagePosition,
            'inchesribcageAngle' => $client->unit == 'Imperial' ? number_format(abs($inches3[3]),2) : number_format(abs($inches3[3] * 2.54),2),
            'incheshipsPosition' =>  $incheshipsPosition,
            'incheshipsAngle' => $client->unit == 'Imperial' ? number_format(abs($inches3[5]),2) : number_format(abs($inches3[5] * 2.54),2),
            'image_path3' => $posture->image_path3
        ];
        if($leftInches2[0] > 0){
            $headPosition ='backward';
        }else{
            $headPosition ='forward';
        }
        if($leftInches2[1] > 0){
            $shoulderPosition ='backward';
        }else{
            $shoulderPosition ='forward';
        }
        if($leftInches2[2] > 0){
            $hipsPosition ='backward';
        }else{
            $hipsPosition ='forward';
        }
        if($leftInches2[3] > 0){
            $kneePosition ='backward';
        }else{
            $kneePosition ='forward';
        }
     
        if($leftInches2[4] > 0){
            $kneePosition ='backward';
        }else{
            $kneePosition ='forward';
        }
     
        $postureData['image4_data'] = [
            'headPosition' =>$headPosition,
            'shoulderPosition' =>$shoulderPosition,
            'hipsPosition' =>$hipsPosition,
            'kneePosition' =>$kneePosition,
            'inchesheadPosition' =>$inchesheadPosition,
            'inchesheadAngle' =>  $client->unit == 'Imperial' ? number_format(abs($leftInches2[0]),2) : number_format(abs($leftInches2[0] * 2.54),2),
            'inchesshoulderAngle' =>  $client->unit == 'Imperial' ? number_format(abs($leftInches2[1]),2) : number_format(abs($leftInches2[1] * 2.54),2),
            'inchesHipsAngle' => $client->unit == 'Imperial' ? number_format(abs($leftInches2[2]),2) : number_format(abs($leftInches2[2] * 2.54),2),
            'incheskneeAngle' => $client->unit == 'Imperial' ? number_format(abs($leftInches2[3]),2) : number_format(abs($leftInches2[3] * 2.54),2),
            'headWeight1' => $client->unit == 'Imperial' ? number_format(abs($leftInches2[4]),2) : number_format(abs($leftInches2[4]*0.453592),2),
            'headWeight2' => $client->unit == 'Imperial' ? number_format(abs($leftInches2[5]),2) : number_format(abs($leftInches2[5]*0.453592),2),
            // 'headWeight1' => number_format(abs($leftInches2[4]),2),
            // 'headWeight2' => number_format(abs($leftInches2[5]),2),
            'image_path4' => $posture->image_path4

        ];
        if($client->unit == 'Imperial'){
            $unit_in = 'lb';
            $set_unit = '"';
        }else{
            $unit_in = 'kg';
            $set_unit = 'cm';
        }

        foreach($postureData as $key => $value){
            if($key == 'angle'){

            }
        }
        //    $pdf = PDF::loadView('mealplanner/meal/mealpdf', ['mealData' => $mealData]);
        //    return $pdf->download(strtolower(str_replace(' ','-',$mealData['name'])).'.pdf');
        $folderPath = public_path().'/posture-pdf/';
        $pdfName = 'posture-report_'.time().'.pdf';
        $pdf = PDF::loadView('posture.posture-pdf', ['postureData' => $postureData,'unit_in'=>$unit_in,'set_unit'=>$set_unit])->save($folderPath.$pdfName);
        $posture->update(['pdf_name' =>$pdfName ]);
        if($mail == 1){
            return $pdfName;
        }else{
        return $pdf->download($pdfName);
        }
    }

    public function editAnalysis(Request $request){
        $posture = PostureImage::where('id',$request->posture_id)->first();
        if($posture->added_from == 0){

            if(!empty($posture->image1) && $posture->added_from1 == 0){
                $notification = [
                    'status' => 'crop',
                    'image_name' => $posture->image1,
                    'posture_id' => $posture->id,
                    'image_type' => 'image1',
                ];
                return response()->json($notification);
            }elseif(!empty($posture->image2) && $posture->added_from2 == 0){
                $notification = [
                    'status' => 'crop',
                    'image_name' => $posture->image2,
                    'posture_id' => $posture->id,
                    'image_type' => 'image2',
                ];
                return response()->json($notification);
            }elseif(!empty($posture->image3) && $posture->added_from3 == 0){
                $notification = [
                    'status' => 'crop',
                    'image_name' => $posture->image3,
                    'posture_id' => $posture->id,
                    'image_type' => 'image3',
                ];
                return response()->json($notification);
            }elseif(!empty($posture->image4) && $posture->added_from4 == 0){
                $notification = [
                    'status' => 'crop',
                    'image_name' => $posture->image4,
                    'posture_id' => $posture->id,
                    'image_type' => 'image4',
                ];
                return response()->json($notification);
            }else{
                return view('posture.edit-posture',compact('posture'));
            }
        }elseif($posture->added_from == 1){
            return view('posture.edit-posture',compact('posture'));
        }
        
    }

    public function preview($posture){
        $client = Clients::select('id','unit')->where('id',$posture->client_id)->first();
        $posture_id = $posture->id;
        $client_id = $posture->client_id;
        $unit = $client->unit;
        $image1_data = [
            'image1' => $posture->image1,
            'xpos1' => $posture->xpos1,
            'ypos1' => $posture->ypos1,
            'angle1' => $posture->angle1,
            'front_inches' => $posture->front_inches,
            'image_path1' => $posture->image_path1,
        ];
        $image2_data = [
            'image2' => $posture->image2,
            'xpos2' => $posture->xpos2,
            'ypos2' => $posture->ypos2,
            'angle2' => $posture->angle2,
            'right_inches' => $posture->right_inches,
            'image_path2' => $posture->image_path2,
        ];
        $image3_data = [
            'image3' => $posture->image3,
            'xpos3' => $posture->xpos3,
            'ypos3' => $posture->ypos3,
            'angle3' => $posture->angle3,
            'back_inches' => $posture->back_inches,
            'image_path3' => $posture->image_path3,
        ];
        $image4_data = [
            'image4' => $posture->image4,
            'xpos4' => $posture->xpos4,
            'ypos4' => $posture->ypos4,
            'angle4' => $posture->angle4,
            'left_inches' => $posture->left_inches,
            'image_path4' => $posture->image_path4,
        ];
        return view('posture.view-posture',compact('posture_id','client_id','unit','image1_data','image2_data','image3_data','image4_data'));
    }

    public function previewAnalysis(Request $request){
        $posture = PostureImage::where('id',$request->posture_id)->first();
        return $this->preview($posture);
    }

    public function mailpdf(Request $request){
        $posture  = PostureImage::where('id',$request->id)->first();
        $clients  = Clients::where('id',$request->clientId)->first();
        $folderPath = public_path().'/posture-pdf/';

        if($posture->pdf_name != '' && $posture->pdf_name == null){
            $pdfPath = $folderPath.$posture->pdf_name;

        }else{
           $pdfName = $this->generatePdf($request->id,$mail=1);
            $pdfPath = $folderPath.$pdfName;
        }
		$subj ="Epic Trainer: Posture Analysis";
	  	$currentDateTime = date('Y/m/d h:i:sa');
	  	$mailData = [
	  			'subject' => $subj,
	  			'name' => $clients->firstname.' '.$clients->lastname,
				'toEmail' => $clients->email,
				'filePath' => $pdfPath
	  		];
  		$templateData = [
			  'name' => $clients->firstname.' '.$clients->lastname
  		];
	  	$html = view('posture.mail-pdf',compact('templateData'))->render();
	  	$response = sendMail($mailData,$html,$posture=1);
	  return $response;

    }
    public function deleteReport(Request $request){
        PostureImage::where('id',$request->id)->delete();
        $msg['status'] ='Data delete Successfully';
         return $msg;
     }

     public function deleteImage(Request $request)
    { 
        $posture = PostureImage::where('id',$request->id)->first();
        if($request->image_name == 'image1'){
            $data = [
                'image1' => null,
                'xpos1' => null,
                'ypos1' => null,
                'angle1' => null,
                'front_inches' => null,
                'image_path1' => null,
            ];
        }
        if($request->image_name == 'image2'){
            $data = [
                'image2' => null,
                'xpos2' => null,
                'ypos2' => null,
                'angle2' => null,
                'right_inches' => null,
                'image_path2' => null,
            ];
        }
        if($request->image_name == 'image3'){
            $data = [
                'image3' => null,
                'xpos3' => null,
                'ypos3' => null,
                'angle3' => null,
                'back_inches' => null,
                'image_path3' => null,
            ];
        }
        if($request->image_name == 'image4'){
            $data = [
                'image4' => null,
                'xpos4' => null,
                'ypos4' => null,
                'angle4' => null,
                'left_inches' => null,
                'image_path4' => null,
            ];
        }
        if($posture) {
            $posture->update($data);
            $posture = PostureImage::where('id',$request->id)->first();
            if(empty($posture->image1) && empty($posture->image2) && empty($posture->image3) && empty($posture->image4)){
                $posture->delete();
                $data = [
                    'status' => 'delete-record',
                    'msg' => 'No more image found',
                ];
            }else{
                $data = [
                    'status' => true,
                    'msg' => 'Image removed successfully',
                ];
            }
            
        }else{
           
            $data = [
                'status' => false,
                'msg' => 'Record not found',
            ];
        }
       
        return response()->json($data);
    }

    
  
public function saveHeightWeight(Request $request){
    PersonalMeasurement::create([
        'client_id' => $request->clientId,
        'event_date'=> Carbon::now()->format('Y-m-d'),
        'height' => $request->height,
       'weight' => $request->weight,
        'weightUnit' => $request->weightUnit,
        'heightUnit' =>  $request->heightUnit,
        'updated_date' => Carbon::now()->format('Y-m-d')
    ]);

}

public function saveNote(Request $request){
    $posture = PostureImage::where('id',$request->posture_id)->first();
    if($request->image == 'front-image'){
        if(!empty($posture->image1)){
            $data = [
                'client_id' => $request->client_id,
                'front_note' => $request->note,
            ];
        }else{
            $res = [
                'status' => 'error',
                'msg' => 'First upload front image',
            ];
            return response()->json($res);
        }
    }
    if($request->image == 'right-image'){
        if(!empty($posture->image2)){
            $data = [
                'client_id' => $request->client_id,
                'right_note' => $request->note,
            ];
        }else{
            $res = [
                'status' => 'error',
                'msg' => 'First upload right image',
            ];
            return response()->json($res);
        }
    }
    if($request->image == 'back-image'){
        if(!empty($posture->image3)){
            $data = [
                'client_id' => $request->client_id,
                'back_note' => $request->note,
            ];
        }else{
            $res = [
                'status' => 'error',
                'msg' => 'First upload back image',
            ];
            return response()->json($res);
        }
    }
    if($request->image == 'left-image'){
        if(!empty($posture->image4)){
            $data = [
                'client_id' => $request->client_id,
                'left_note' => $request->note,
            ];
        }else{
            $res = [
                'status' => 'error',
                'msg' => 'First upload left image',
            ];
            return response()->json($res);
        }
    }
    if($posture) {
        $posture->update($data);
        $res = [
            // 'status' => 'update',
            'posture_id' => $posture->id,
            'image' => $request->image,
            'note' => $request->note,
        ];
        return response()->json($res);
    }
    
}


}

