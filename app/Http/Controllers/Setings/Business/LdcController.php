<?php
namespace App\Http\Controllers\Setings\Business;

use App\Clas;
use App\ClassCat;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use DB;
use App\UserType;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\SalesTools\Invoice\SalesToolsInvoiceTrait;
use App\Http\Traits\CalendarSettingTrait;
use App\Http\Traits\ChartSettingTrait;
use App\Ldc;
use App\Service;
use App\Session as AppSession;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Str;
use Throwable;
use File;

class LdcController extends Controller{

    public function index(){
        $ldcData = Ldc::get();
        return view('Settings.ldc.index',compact('ldcData'));
        
    }

    public function edit($id){
       $ldcData = Ldc::where('ldc_id',$id)->first();
       $servicesData = json_decode($ldcData->ldc_services);
       $sessionsData = json_decode($ldcData->ldc_sessions);
       $pdfData = json_decode($ldcData->ldc_pdf);
       $servicesNew = [];
       $services = Service::where('business_id', Session::get('businessId'))->select('id', 'category', 'team_name', 'one_on_one_name', 'team_price', 'one_on_one_price')->get();
        foreach ($services as $serivesData) {
            if ($serivesData->category == 1) {
                // TEAM
                $servicesNew[$serivesData->id]  = ucfirst($serivesData->team_name);
            } else if ($serivesData->category == 2) {
                // 1 on 1
                $servicesNew[$serivesData->id]  = ucfirst($serivesData->one_on_one_name);
            }
        }
       $clsCatNew = [];
       $clsCat = ClassCat::OfBusiness()->select('clcat_id','clcat_value')->get();
       foreach($clsCat as $class){
           $clsCatNew[$class->clcat_id] = $class->clcat_value;
       }
       return view('Settings.ldc.create', compact('ldcData','servicesData','sessionsData','pdfData','clsCatNew','servicesNew','services','clsCat'));
    }

    public function create(){
        $services = [];
        	$services = Service::OfBusiness()
    					->complOnly()
                        ->where(function($query){
                            $query->where('one_on_one_staffs', '<>', '')
                                  ->orWhere('team_staffs', '<>', '');
                        })
                        ->get(array('id', 'one_on_one_name','category', 'one_on_one_duration', 'one_on_one_price','one_on_one_tax', 'team_name', 'team_duration', 'team_price','team_tax', 'for_sales_process_step'));
                       
            $clsCat = [];
            $clsCat = ClassCat::OfBusiness()->get(array('clcat_id','clcat_value'));
       
       
        return view('Settings.ldc.create', compact('services','clsCat'));
    }
    public function uploadPdf(Request $request){
        $file = $_FILES['file']['name'];
            $timestamp = md5(time().rand());
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if($extension == 'pdf' || $extension == 'PDF' || $extension == 'Pdf'){
            $name = $timestamp.'.'.$extension;
            // if(!File::isDirectory('uploads/calenderPdf')){
            //     File::makeDirectory('uploads/calenderPdf', 0777, true, true); 
            // }
            if(move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $name))
            {
               $msg['name'] = $name; 
               $msg['fileOriginalName'] = $_FILES['file']['name'];
               $msg['status'] = 'success';
            }
        }else{
             $msg['status'] = 'error';
        }

        return json_encode($msg);
        
    }
public function saveLdcData(Request $request){
    $insertData = array('ldc_name' => $request->ldcname, 'ldc_start_date'=>$request->startDate, 'ldc_end_date'=>$request->endDate);
    if($request->id){
        $insertData['ldc_services'] = [];
        $insertData['ldc_sessions']= [];
        $insertData['ldc_pdf'] = [];
    }
     
     if($request->services){
        $services     = $request->services;
        $services_limit= array();
        $mem_service = array();
        $mem_limit    = array();
        $mem_type     = array();
        if (count($services)) {
            ksort($services);
            foreach ($services as $key => $value) {
                if (strpos($key, 'mem_service') !== false) {
                    $mem_service[] = $value;
                } else if (strpos($key, 'mem_limit') !== false) {
                    $mem_limit[] = (int) $value;
                } else if (strpos($key, 'mem_type') !== false) {
                    $mem_type[] = $value;
                } 

            }
        }
        if (count($mem_service) && count($mem_limit) && count($mem_type)) {
            for ($i = 0; $i < count($mem_service); $i++) {
                $services_limit[$mem_service[$i]] = array('id'=>$i,'limit' => $mem_limit[$i], 'limit_type' => $mem_type[$i]);
            }
        } else {
            $services_limit = [];
        }

        $insertData['ldc_services'] = json_encode($services_limit);
    }
    if($request->sessions){
        $sessions     = $request->sessions;
        $mem_session = array();
        $sessions_limit= array();
        $session_mem_limit    = array();
        $session_mem_type     = array();
        if (count($sessions)) {
            ksort($sessions);
            foreach ($sessions as $key => $value) {
                if (strpos($key, 'mem_session') !== false) {
                    $mem_session[] = $value;
                } else if (strpos($key, 'session_mem_limit') !== false) {
                    $session_mem_limit[] = (int) $value;
                } else if (strpos($key, 'session_mem_type') !== false) {
                    $session_mem_type[] = $value;
                } 

            }
        }
        if (count($mem_session) && count($session_mem_limit) && count($session_mem_type)) {
            for ($i = 0; $i < count($mem_session); $i++) {
                $sessions_limit[$mem_session[$i]] = array('id'=>$i,'limit' => $session_mem_limit[$i], 'limit_type' => $session_mem_type[$i]);
            }
        } else {
            $sessions_limit = [];
        }

        $insertData['ldc_sessions'] = json_encode($sessions_limit);
    }
    if($request->pdf){
        $pdf     = $request->pdf;
        $pdf_file = array();
        $pdf_limit= array();
        $pdfOriginalName = array();
        $pdfStartDate    = array();
        if (count($pdf)) {
            ksort($pdf);
            foreach ($pdf as $key => $value) {
                if (strpos($key, 'pdfFile') !== false) {
                    $pdf_file[] = $value;
                } else if (strpos($key, 'pdfStartDate') !== false) {
                    $pdfStartDate[] = $value;
                }else if (strpos($key, 'pdfOriginalName') !== false) {
                    $pdfOriginalName[] = $value;
                }

            }
        }
        if (count($pdf_file) && count($pdfStartDate) && count($pdfOriginalName)) {
            for ($i = 0; $i < count($pdf_file); $i++) {
                $pdf_limit['file'.$i] = array('id'=>$i,'file_name'=>$pdf_file[$i],'original_name'=>$pdfOriginalName[$i],'pdfStartDate' => $pdfStartDate[$i]);
            }
        } else {
            $pdf_limit = [];
        }
        $insertData['ldc_pdf'] = json_encode($pdf_limit);
    }
    if($request->id){
        $ldcData = Ldc::where('ldc_id',$request->id)->update($insertData);
    }else{
    $ldcData = Ldc::create($insertData);
    }
    if(count($ldcData)){
        $msg['status'] = 'success';
    }else{
        $msg['status'] = 'failed';
    }
    return json_encode($msg);
}

public function delete($id){
    Ldc::where('ldc_id',$id)->delete();

    return redirect()->route('ldc.session');
}
public function showLdcList(){
    try{
    $ldcData = Ldc::select('ldc_id','ldc_name')->get();
    $msg = [
        'status' => 'success',
        'data' => count($ldcData)?$ldcData->toArray():[]
    ];
    }catch(\ Throwable $e){
        $msg = [
            'status' => 'error',
            'error' => $e->getMessage()
        ]; 
    }
     return json_encode($msg);
}
}