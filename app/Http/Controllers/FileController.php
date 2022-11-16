<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Image;
use DB;
use Excel;
use Carbon\Carbon;
use App\MpServingSize;
use App\MpFoods;
use App\AbExerciseImage;
use App\AbExerciseVideo;
use App\ActivityVideo;
use App\Imports\FoodImport;
use App\Imports\MealImport;


class FileController extends Controller {
    /*public function getResizeImage(){
        return view('files.resizeimage');
    }*/

    /** 
     * Slice image and save
     * @param File
     * @return Image name, image exetension, status
    **/
    public function postResizeImage(Request $request){

        $msg=[];
        $preImgName = [];
        $exe = ['jpg','png','jpeg','gif','svg'];
        $files = $request->all();
        $photo = $files[0];
        $type = $files['type'];
        $preImg = $files['preImg'];

        $imgExe = $photo->getClientOriginalExtension();
        if(in_array($imgExe, $exe)){
            $imgName = time();
            $imagename = $imgName.'.'.$imgExe; 
            
            $destinationPath = public_path('/uploads');
            if($type == 'category'){
                $preImgName[] = 'cat_85_';
                $name = 'cat_85_'.$imagename;
                $thumb_img0 = Image::make($photo->getRealPath())->resize(800, 500);
                $thumb_img0->save($destinationPath.'/'.$name,80);

                $preImgName[] = 'cat_236_';
                $name = 'cat_236_'.$imagename;
                $thumb_img1 = Image::make($photo->getRealPath())->resize(2340, 616);
                $thumb_img1->save($destinationPath.'/'.$name,80);

                $preImgName[] = 'cat_1012_';
                $name = 'cat_1012_'.$imagename;
                $thumb_img2 = Image::make($photo->getRealPath())->resize(1056, 1284);
                $thumb_img2->save($destinationPath.'/'.$name,80);

                $preImgName[] = 'cat_810_';
                $name = 'cat_810_'.$imagename;
                $thumb_img3 = Image::make($photo->getRealPath())->resize(800, 1000);
                $thumb_img3->save($destinationPath.'/'.$name,80);
            }
            elseif($type == 'product'){
                $preImgName[] = 'prod_85_';
                $name = 'prod_85_'.$imagename;
                $thumb_img0 = Image::make($photo->getRealPath())->resize(800, 500);
                $thumb_img0->save($destinationPath.'/'.$name,80);

                $preImgName[] = 'prod_56_';
                $name = 'prod_56_'.$imagename;
                $thumb_img1 = Image::make($photo->getRealPath())->resize(500, 650);
                $thumb_img1->save($destinationPath.'/'.$name,80);

                $preImgName[] = 'prod_11_';
                $name = 'prod_11_'.$imagename;
                $thumb_img2 = Image::make($photo->getRealPath())->resize(120, 156);
                $thumb_img2->save($destinationPath.'/'.$name,80);

                $preImgName[] = 'prod_23_';
                $name = 'prod_23_'.$imagename;
                $thumb_img3 = Image::make($photo->getRealPath())->resize(285, 375);
                $thumb_img3->save($destinationPath.'/'.$name,80);
            }
                       
            $photo->move($destinationPath, $imagename);// origenal image

            if(count($preImgName)){
                foreach ($preImgName as $name) {
                    $fileName = public_path('/uploads/') . $name.$preImg;
                    if(file_exists($fileName)){
                        unlink($fileName );
                    }
                }
            }
    
            if($preImg && file_exists(public_path('/uploads/') . $preImg))
                unlink(public_path('/uploads/') . $preImg);

            $msg['status'] = 'save';
            $msg['imgname'] = $imgName;
            $msg['imgex'] = $imgExe;
        }
        else{
            $msg['status'] = 'error';
            $msg['message'] = 'Please select vailid image.';
        }

        return json_encode($msg);
    }


    /** 
     * Remove image from file
     * @param image name
     * @return status
    **/
    public function removeImage(Request $request){
        $response['status'] = 'error';
        $imagePath = $request->preImg;
        $imageName = explode('_', $imagePath);

        if(AbExerciseImage::where('aei_image_name', $imageName[1])->delete()){
            // remove copy file
            if(file_exists(public_path('/uploads/') .$imagePath)){
                unlink(public_path('/uploads/') . $imagePath);
                $response['status'] = 'success';
            }

            //remove origenal file
            if(file_exists(public_path('/uploads/') .$imageName[1])){
                unlink(public_path('/uploads/') . $imageName[1]);
                $response['status'] = 'success';
            }
            
        }
        else{
           $response['status'] = 'success'; 
        }
        return json_encode($response);
    }

    public function removeVideo(Request $request){
        $response['status'] = 'error';
        $videoName = $request->preVid;
        // $imageName = explode('_', $imagePath);
        if($request->has('source') && $request->source == 'activityVideo'){
            if(ActivityVideo::where('video', $videoName)->update(['video' => ''])){
               // remove copy file
                if(file_exists(public_path('/uploads/') .$videoName)){
                    unlink(public_path('/uploads/') . $videoName);
                    $response['status'] = 'success';
                }

                //remove origenal file
                if(file_exists(public_path('/uploads/') .$videoName)){
                    unlink(public_path('/uploads/') . $videoName);
                    $response['status'] = 'success';
                } 
            }else{
                $response['status'] = 'error';
            }
        }else{
            if(AbExerciseVideo::where('aei_video_name', $videoName)->delete()){
                // remove copy file
                if(file_exists(public_path('/uploads/') .$videoName)){
                    unlink(public_path('/uploads/') . $videoName);
                    $response['status'] = 'success';
                }
    
                //remove origenal file
                if(file_exists(public_path('/uploads/') .$videoName)){
                    unlink(public_path('/uploads/') . $videoName);
                    $response['status'] = 'success';
                }
                
            }
            else{
               $response['status'] = 'success'; 
            }
        }
        return json_encode($response);
    }

    /**
     * Excel file open
     * 
     * @param
     * @return
     */
    public function show(){
        return view('excel-file-import');
    }

    /**
     * Excel data import
     *
     * @param
     * @return
     */
    public function importExcelIntoDB(Request $request){
        $requestData = $request->all();
        $exe = ['xls','xlsx','csv'];       
        $excel = $requestData[0];
        $excelExe = $excel->getClientOriginalExtension();
        if(in_array($excelExe, $exe)){
            try{
                if($requestData['import-type'] == 'meal'){
                    Excel::import(new MealImport, $excel);
                }else{
                    Excel::import(new FoodImport, $excel);
                }
                $response['status'] = 'success';
                $response['msg'] = 'Rows inserted successfuly!';
                return json_encode($response);
            } catch(\Exception $e){
                $response['status'] = 'error';
                $response['msg'] = 'Request data does not imported, Might be something error.';
                return json_encode($response); 
            }
        }
        $response['status'] = 'error';
        $response['msg'] = 'Invalid File Format.'; 
        return json_encode($response);
    }

    /**
     * Get unit name and tag
     *
     * @param String unit
     * @param Array unitname, tag name
     */
    protected function getUnitWithTag($name){
        $response = array('unit'=>'','tags'=>'');
        switch ($name) {
            case 'g':
            case 'gr':
            case 'gm':
            case 'gram':
                $response['unit'] = 'Gram';
                $response['tags'] = 'g,gr,grm,gram'; 
                break;

            case 'm':
            case 'ml':
                $response['unit'] = 'ml';
                $response['tags'] = 'm,ml'; 
                break;
        }
        return $response;
    }

}