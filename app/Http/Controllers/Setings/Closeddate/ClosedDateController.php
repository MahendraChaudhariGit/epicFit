<?php
namespace App\Http\Controllers\Setings\Closeddate;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ClosedDate;
use Session;
use App\Http\Traits\HelperTrait;
use Input;
use App\StaffEventClass;
use App\StaffEventSingleService;
use App\Http\Traits\ClosedDateTrait;
use Carbon\Carbon;
//use DB;

class ClosedDateController extends Controller{
    use HelperTrait, ClosedDateTrait;

    private $cookieSlug = 'closed-date';
    
    public function index(){
        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'list-closed-dates'))
            abort(404);

        $alldates = array();
        $search = Input::get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        if($search)
            $alldates = ClosedDate::where('cd_business_id',Session::get('businessId'))->where('cd_description', 'like', "%$search%")->paginate($length);
        else
            $alldates = ClosedDate::where('cd_business_id',Session::get('businessId'))->paginate($length);
        
        return view('Settings.closeddate.index', compact('alldates'));
    }

    public function create(){
        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'add-closed-dates'))
            abort(404);
        
        return view('Settings.closeddate.create');
    }

    public function store(Request $request){
        if(!isUserEligible(['Admin'], 'add-closed-dates')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }
        
        $isError = false;
        if($request->ajax())
            $msg = [];

        /*if($this->getOverlappingDates($request->startdate, $request->enddate)){
            $msg['status'] = 'error';
            $msg['errorData'][] = array('overlappingDates' => 'Dates overlap with an existing date period!');
            $isError = true;
        }*/
        $overlappingDates = $this->getOverlappingDates($request->startdate, $request->enddate);
        if(count($overlappingDates)){
            $msg['status'] = 'error';
            $msg['errorData'][] = array('overlappingDates' => json_encode($overlappingDates));
            $isError = true;
        }

        if(!$isError){
            $closeddate = new ClosedDate;
            $closeddate->cd_start_date = $request->startdate;
			$closeddate->cd_end_date = $request->enddate;
			$closeddate->cd_description = $request->description;
			$closeddate->cd_business_id = Session::get('businessId');
			
            if($closeddate->save()){
                Session::put('ifBussHasClosedDates' , true);
                if($request->ajax()){
                    $msg['status'] = 'added';
                    $msg['message'] = displayAlert('success|Date has been saved successfully.');
                }
                else
                    Session::flash('flash_message', 'Date has been saved successfully.');
            }
        }

        if($request->ajax())
            return json_encode($msg);
        else{
            if($isError)
                abort(404);
            else
                return redirect('Settings/closeddate');
        }
    }


    public function edit($id){
         if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-closed-dates'))
            abort(404);
        $closedDate = ClosedDate::where('cd_business_id',Session::get('businessId'))->select('cd_id','cd_start_date','cd_end_date','cd_description')->find($id);

        return view('Settings.closeddate.edit', compact('closedDate'));
    }

    public function update($id, Request $request){
        $isError = false;

        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-closed-dates')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if($request->ajax())
            $msg = [];
        
        if(!$isError){
            $overlappingDates = $this->getOverlappingDates($request->startdate, $request->enddate, $request->closeddateid);
            if(count($overlappingDates)){
                $msg['status'] = 'error';
                //$msg['errorData'][] = array('overlappingDates' => 'Dates overlap with an existing date period!');
                $msg['errorData'][] = array('overlappingDates' => json_encode($overlappingDates));
                $isError = true;
            }
            if(!$isError){
                $data = ClosedDate::where('cd_business_id',Session::get('businessId'))->find($request->closeddateid);
                $data->cd_start_date = $request->startdate;
                $data->cd_end_date = $request->enddate;
                $data->cd_description = $request->description;
                $data->save();

                if($request->ajax()){
                    $msg['status'] = 'updated';
                    $msg['message'] = displayAlert('success|Date has been updated successfully.');
                }
                else
                    Session::flash('flash_message', 'Date has been updated successfully.');
            }
        }
		
        if($request->ajax())
            return json_encode($msg);
        else{
           if($isError)
               abort(404);
         else
            return redirect('admin');
        }
    }

    public function destroy($id){
        if(!isUserEligible(['Admin'], 'delete-closed-dates'))
            abort(404);
        
        $closeddate = ClosedDate::where('cd_business_id', Session::get('businessId'))->findOrFail($id);
        $closeddate->delete();

        if(!ClosedDate::where('cd_business_id',Session::get('businessId'))->exists())
                Session::forget('ifBussHasClosedDates');

        return redirect()->back()->with('message', 'success|Date has been deleted successfully.');
    }

    /**
     *Get exisiting closed dates/booking that overlap with given closed date
     *
     * @param date $startDate Start date of closing period
     * @param date $endDate End date of closing period
     * @param int $id PK of the closing period. Optional
     *
     * @return array Overlapping closed dates
     */
    protected function getOverlappingDates($startDate, $endDate, $id = 0){
        $returnData = [];
        $overlappingDates = ClosedDate::getOverlapping($startDate, $endDate, $id); //Getting closed dates
        $overlappingDates = $this->calcClosedDates($overlappingDates);
        $kase = 'closed';
        if(!$overlappingDates){
            $overlappingClasses = StaffEventClass::ofBusiness()->where('sec_start_datetime', '<=', $endDate.' 23:59:59')->where('sec_end_datetime', '>=', $startDate.' 00:00:00')->distinct()->select('sec_date')->orderBy('sec_date')->get();
            if($overlappingClasses->count()){
                $overlappingDates = $overlappingClasses->implode('sec_date', ',');
                $kase = 'booking';
            }
            else{
                $overlappingServices = StaffEventSingleService::ofBusiness()->where('sess_start_datetime', '<=', $endDate.' 23:59:59')->where('sess_end_datetime', '>=', $startDate.' 00:00:00')->distinct()->select('sess_date')->orderBy('sess_date')->get();
                if($overlappingServices->count()){
                    $overlappingDates = $overlappingServices->implode('sess_date', ',');
                    $kase = 'booking';
                }
            }            
        }
        
        if($overlappingDates){
            $overlappingDates = explode(',', $overlappingDates);
            $startDateCarb = new Carbon($startDate);
            $endDateCarb = new Carbon($endDate);
            $formattedOverlapDates = [];
            foreach($overlappingDates as $overlappingDate){
                $overlappingDate = new Carbon($overlappingDate);
                if(($overlappingDate->eq($startDateCarb) || $overlappingDate->gt($startDateCarb)) && ($overlappingDate->eq($endDateCarb) || $overlappingDate->lt($endDateCarb))){

                    $formattedOverlapDates[] = $overlappingDate->format('j M Y');
                }
            }
            if(count($formattedOverlapDates)){
                $returnData['kase'] = $kase;
                $returnData['dates'] = $formattedOverlapDates;
            }
        }
        return $returnData;

        /*if(!$overlappingDates){
            $overlappingClasses = StaffEventClass::ofBusiness()->where('sec_start_datetime', '<=', $endDate.' 23:59:59')->where('sec_end_datetime', '>=', $startDate.' 00:00:00')->exists();
            if(!$overlappingClasses){
                $overlappingServices = StaffEventSingleService::ofBusiness()->where('sess_start_datetime', '<=', $endDate.' 23:59:59')->where('sess_end_datetime', '>=', $startDate.' 00:00:00')->exists();
                if(!$overlappingServices)
                    return false;
            }
        }
        return true;*/
    }
}
