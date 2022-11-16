<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MeasurementFile;

class MeasurementFileController extends Controller
{
    public function saveFile(Request $request)
    {
        if($request->id){
            $file = MeasurementFile::find($request->id);
        }
        
    	if($file_name = $request->file('file_name')){
            if($request->hasfile('file_name')){
                $full_name = $request->file('file_name')->getClientOriginalName();
                $filename = pathinfo($full_name, PATHINFO_FILENAME);
                $extension = pathinfo($full_name, PATHINFO_EXTENSION);
                $name = $filename.'_'.time().'.'.$extension;
                $file_name->move('attachment-file/', $name);  
                $image = $name;
            }
        }else{
            $image = $file->file_name;
        }
        $data = [
            'client_id' => $request->client_id,
            'file_name' => $image,
            'description' => $request->description,
        ];
        if(isset($file)){
            $file->update($data);
            $all_data = MeasurementFile::where('client_id',$request->client_id)->orderBy('id','desc')->get()->toArray();
            $data = [
                'status' => true,
                'msg' => 'Record updated successfully',
                'data' => $all_data
            ];
        }else{
            $save = MeasurementFile::create($data);
            if(isset($save)){
                $array_data = [];
                $all_data = MeasurementFile::where('client_id',$request->client_id)->orderBy('id','desc')->get()->toArray();
                $data = [
                    'status' => true,
                    'msg' => 'Record added successfully',
                    'data' => $all_data
                ];
            }else{
                $data = [
                    'status' => false,
                    'msg' => 'Please try again.'
                ];
            }
        }
    	return response()->json($data);
    }

    public function editFile($id)
    {
    	$file = MeasurementFile::find($id);
        if(isset($file)){
            $data = [
                'status' => true,
                'data' => $file
            ];
            
        }else{
            $data = [
                'status' => false,
                'data' => null
            ];
        }

    	return response()->json($data);
    }

    public function deleteFile($id)
    {
        $file = MeasurementFile::find($id);
        $client_id = $file->client_id;
        if(isset($file)){
            $file->delete();
            $all_data = MeasurementFile::where('client_id',$client_id)->orderBy('id','desc')->get()->toArray();
            $data = [
                'status' => true,
                'msg' => 'Record deleted successfully',
                'data'=> $all_data,
            ];
        }else{
            $data = [
                'status' => false,
                'msg' => 'Record not found'
            ];
        }

        return response()->json($data);
    	
    }
}
