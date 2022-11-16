<?php

namespace App\Http\Controllers\Result;

use App\FtTaxPreference;
use App\FtTaxSlab;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Traits\Result\HelperTrait;
use App\Http\Traits\Result\ClientEventsTrait;
use App\Http\Traits\LocationAreaTrait;
use App\Result\User;
use App\Clients;
use App\Staff;
use App\Makeup;
use App\Contact;
use App\ClientNote;
use App\MemberShip;
use App\ClientMember;
use App\Parq;
use App\NutritionalJournal;
use App\Business;
use App\Service;
use Auth;
use Carbon\Carbon;
use Session;
use DB;
use App\Http\Traits\StaffEventTrait;
use App\FinanaceTool;
use Talk;
use App\FtSetup;
use App\FtPartnership;
use App\ClientMenu;
use App\Result\Models\MeasurementGalleryImage;
use App\Result\Models\MeasurementBeforeAfterImage;
use App\Result\Models\FinalProgressPhoto;
use Redirect;
use Image;

class ProfileController extends Controller {
    use HelperTrait, ClientEventsTrait, LocationAreaTrait, StaffEventTrait;

    /**
     * show client profile details
     * @param void
     * @return profile view
    **/
    public function edit() {
        $businessId = Auth::user()->business_id;
        $clientId = Auth::user()->account_id;
        $countries = \Country::getCountryLists();
        $currencies = \Currency::$currencies;
        $timezones = \TimeZone::getTimeZone();

        $client = Clients::with('parq')->find($clientId);
        $parq = $client->parq;

        $this->deleteExpiringAspirantsEvents();
        $notes = $client->notes()->orderBy('created_at', 'desc')->first();

        $eventsListData = $this->eventsListForOverview($client);
        $pastEvents = $eventsListData['pastEvents'];
        $latestPastEvent = $eventsListData['latestPastEvent'];
        $futureEvents = $eventsListData['futureEvents'];
        $oldestFutureEvent = $eventsListData['oldestFutureEvent'];
        $modalLocsAreas = $eventsListData['modalLocsAreas'];
        $eventRepeatIntervalOpt = $eventsListData['eventRepeatIntervalOpt'];

        $latestPastEventInMembership = $eventsListData['latestPastEventInMembership'];
        $oldestFutureEventInMembership = $eventsListData['oldestFutureEventInMembership'];

        if ($client) {
            $client->account_status_backend = $this->getStatusForbackend($client->account_status, true);
            if ($parq->dob != '0000-00-00') {
                $carbonDob = Carbon::createFromFormat('Y-m-d', $parq->dob);
                $overviewDob = $carbonDob->format('D, j M Y');
                $parq->birthYear = $carbonDob->year;
                $parq->birthMonth = $carbonDob->month;
                $parq->birthDay = $carbonDob->day;
            } else {
                $overviewDob = $parq->birthYear = $parq->birthMonth = $parq->birthDay = '';
            }
            $client->goalHealthWellness = explode(',', $client->parq->goalHealthWellness);
        }

        /* membership start */
        $clients = Clients::with('parq')->find($clientId);
        $allMemberShipData = [];
        $salesProcessDetails = calcSalesProcessRelatedStatus($clients->sale_process_step);
        if (array_key_exists("clientStatus", $salesProcessDetails))
            $salesProcessStepStatus = $salesProcessDetails['clientStatus'];
        else
            $salesProcessStepStatus = $salesProcessDetails['clientPrevStatus'];

        $membershipClasses = $membershipServices = '';
        $memberShip = MemberShip::where('me_business_id', $businessId)->get();
        foreach ($memberShip as $mValue) {
            $allMemberShipData[$mValue['id']] = ['name' => $mValue->me_membership_label, 'length' => $mValue->me_validity_length, 'lengthUnit' => $mValue->me_validity_type];
        }
        $selectedMemberShip = $clients->membership($clients->id);
        $activeMemb = $endDateReaching = $dueDateReaching = 0;
        if ($selectedMemberShip) {
            if($selectedMemberShip->cm_classes){
                $classes = json_decode( $selectedMemberShip->cm_classes, true);
                $membershipClasses = [];
                foreach($classes as $id => $name)
                    $membershipClasses[] = $name;
                $membershipClasses = implode(', ', $membershipClasses);
            }

            if($selectedMemberShip->cm_services_limit){
                $services = json_decode( $selectedMemberShip->cm_services_limit, true);
                $membershipServices = [];
                foreach($services as $id => $value)
                    $membershipServices[] = Service::getServiceName($id);

                $membershipServices = implode(', ', $membershipServices);
            }

            if ($selectedMemberShip->cm_status == 'Paid' || $selectedMemberShip->cm_status == 'Unpaid') {
                $activeMemb = $selectedMemberShip;

                if (count($allMemberShipData)) {
                    $allMemberShipData[$activeMemb->cm_membership_id]['length'] = $activeMemb->cm_validity_length;
                    $allMemberShipData[$activeMemb->cm_membership_id]['lengthUnit'] = $activeMemb->cm_validity_type;
                }

                if ($activeMemb->cm_status == 'Paid') {
                    $now = new Carbon();

                    $endDateCarb = new Carbon($activeMemb->cm_end_date);
                    if ($endDateCarb->gt($now)) {
                        $prev5thDay = $endDateCarb->subDays(4);
                        if ($prev5thDay->lte($now)) {
                            if ($activeMemb->cm_auto_renewal == 'on')
                                $endDateReaching = 1; //Will be renewed
                            else
                                $endDateReaching = -1; //Will expire
                        }
                    }

                    $dueDateCarb = new Carbon($activeMemb->cm_due_date);
                    if ($dueDateCarb->gt($now)) {
                        $prev5thDay = $dueDateCarb->subDays(4);
                        if ($prev5thDay->lte($now))
                            $dueDateReaching = 1;
                    }
                }
            }
        }

        $membershipHistory = ClientMember::where('cm_client_id', $clients->id)->latest()->get();
        $this->deleteExpiringAspirantsEvents();

        $makeUpCount = $clients->makeup_session_count;
        $lastLimitCount = 0;
        $nextLimitCount = 0;
        if (count($pastEvents)) {
            $makeUpCount += $this->getMakeUpCount($pastEvents);
        }
        if (count($pastEvents) && $selectedMemberShip) {
            $type = $selectedMemberShip->cm_class_limit_type;
            // dd($type);
            $cmid = $selectedMemberShip->id;
            $lastLimitData = [];
            $startDate = $endDate = '';
            if ($type == "every_week") {
                $startDate = (new Carbon())->startOfWeek()->toDateString();
                $endDate = (new Carbon())->toDateString();
            } elseif ($type == "every_month") {
                $startDate = (new Carbon())->startOfMonth()->toDateString();
                $endDate = (new Carbon())->toDateString();
            }
            if ($startDate && $endDate) {
                $lastLimitData = $pastEvents->filter(function($pastEvent) use ($cmid, $startDate, $endDate) {
                    $model = class_basename($pastEvent);
                    return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null && $pastEvent->pivot->deleted_at == null && ($pastEvent->pivot->secc_client_status != 'Waiting' || $pastEvent->pivot->secc_if_make_up) && $pastEvent->pivot->secc_cmid != 0 && $pastEvent->sec_date >= $startDate && $pastEvent->sec_date <= $endDate) || ($model == 'StaffEventSingleService' && $pastEvent->deleted_at == null && $pastEvent->sess_booking_status == 'Confirmed' && $pastEvent->sess_date >= $startDate && $pastEvent->sess_date <= $endDate && $pastEvent->sess_cmid != 0);
                });
                $lastLimitCount = count($lastLimitData);
            }
        }
        if (count($futureEvents)) {
            $makeUpCount += $this->getMakeUpCount($futureEvents);
        }
        if (count($futureEvents) && $selectedMemberShip) {
            $nextLimitData = [];
            $type = $selectedMemberShip->cm_class_limit_type;
            $cmid = $selectedMemberShip->id;
            $startDate = $endDate = '';
            if ($type == "every_week") {
                $startDate = (new Carbon())->toDateString();
                $endDate = (new Carbon())->endOfWeek()->toDateString();
            } elseif ($type == "every_month") {
                $startDate = (new Carbon())->toDateString();
                $endDate = (new Carbon())->endOfMonth()->toDateString();
            }

            if ($startDate && $endDate) {
                $nextLimitData = $futureEvents->filter(function($futureEvent) use($cmid, $startDate, $endDate) {
                    $model = class_basename($futureEvent);
                    return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null && $futureEvent->sec_date > $startDate && $futureEvent->sec_date <= $endDate && $futureEvent->pivot->secc_cmid != 0) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null && $futureEvent->sess_booking_status == 'Confirmed' && $futureEvent->sess_client_attendance == 'Booked' && $futureEvent->sess_date >  $startDate && $futureEvent->sess_date <= $endDate && $futureEvent->sess_cmid != 0);
                });

                $nextLimitCount = count($nextLimitData);
            }
        }
        
        $limitCount = $lastLimitCount + $nextLimitCount;
  
        $parq->isReferenceDeleted = false;
        $clients->account_status_backend = $this->getStatusForbackend($clients->account_status, true);
        if ($parq->dob != '0000-00-00') {
            $carbonDob = Carbon::createFromFormat('Y-m-d', $parq->dob);
            $overviewDob = $carbonDob->format('d M, Y');
            $parq->birthYear = $carbonDob->year;
            $parq->birthMonth = $carbonDob->month;
            $parq->birthDay = $carbonDob->day;
        } else
            $overviewDob = $parq->birthYear = $parq->birthMonth = $parq->birthDay = '';

        if ($parq->addrState)
            $parq->stateName = \Country::getStateName($parq->country, $parq->addrState);

        $parq->paIntensity = explode(',', $parq->paIntensity);

        $parq->headInjury = explode(',', $parq->headInjury);
        $parq->neckInjury = explode(',', $parq->neckInjury);
        $parq->shoulderInjury = explode(',', $parq->shoulderInjury);
        $parq->armInjury = explode(',', $parq->armInjury);
        $parq->handInjury = explode(',', $parq->handInjury);
        $parq->backInjury = explode(',', $parq->backInjury);
        $parq->hipInjury = explode(',', $parq->hipInjury);
        $parq->legInjury = explode(',', $parq->legInjury);
        $parq->footInjury = explode(',', $parq->footInjury);

        $parq->goalHealthWellnessRaw = $parq->goalHealthWellness;
        $parq->goalHealthWellness = explode(',', $parq->goalHealthWellness);

        $parq->headImprove = explode(',', $parq->headImprove);
        $parq->neckImprove = explode(',', $parq->neckImprove);
        $parq->footImprove = explode(',', $parq->footImprove);
        $parq->legImprove = explode(',', $parq->legImprove);
        $parq->handImprove = explode(',', $parq->handImprove);
        $parq->backImprove = explode(',', $parq->backImprove);
        $parq->hipImprove = explode(',', $parq->hipImprove);
        $parq->hamstringsImprove = explode(',', $parq->hamstringsImprove);
        $parq->shouldersImprove = explode(',', $parq->shouldersImprove);
        $parq->armsImprove = explode(',', $parq->armsImprove);
        $parq->calvesImprove = explode(',', $parq->calvesImprove);
        $parq->quadsImprove = explode(',', $parq->quadsImprove);
        $parq->lifestyleImprove = explode(',', $parq->lifestyleImprove);
        $parq->goalWantTobe = explode(',', $parq->goalWantTobe);
        $parq->goalWantfeel = explode(',', $parq->goalWantfeel);
        $parq->goalWantHave = explode(',', $parq->goalWantHave);
        $parq->motivationImprove = explode(',', $parq->motivationImprove);

        $allNotes = ClientNote::select('cn_id', 'cn_client_id', 'cn_type', 'cn_notes', 'created_at')->where('cn_client_id', $clientId)->where('cn_notes', '<>', '')->latest()->orderBy('cn_id', 'desc')->get();


        $allMakeup = Makeup::where('makeup_client_id', $clientId)->where('created_at','>=','2020-05-13')->orderBy('makeup_id', 'desc')->get();


        return view('Result.profile', compact('latestPastEventInMembership','oldestFutureEventInMembership','businessId', 'client', 'overviewDob', 'parq', 'notes', 'countries', 'timezones', 'currencies', 'pastEvents', 'latestPastEvent', 'futureEvents', 'oldestFutureEvent', 'modalLocsAreas', 'eventRepeatIntervalOpt', 'selectedMemberShip', 'membershipHistory', 'membershipServices', 'membershipClasses', 'limitCount','allMakeup','allNotes'));
    }




    /**
     * show client profile details
     * @param id
     * @return profile view
    **/
    public function update($id, Request $request) {
        $isError = false;
        $msg = [];

        if (!$isError) {
            $client = Clients::find($id);
            if ($client) {
                if (!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'client', 'id' => $id])) {
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                    $isError = true;
                }

                if (!$isError) {
                    $client->firstname = $request->first_name;
                    $client->lastname = $request->last_name;
                    // $client->account_status = $this->getStatusForbackend($request->client_status);
                    //$client->email = $request->email;
                    //$client->phonenumber = $request->numb;
                    //$client->notes = $request->client_notes;

                    if (isset($request->gender))
                        $client->gender = $request->gender;
                    /* else
                      $client->gender = ''; */

                    $dob = '';
                    $parq = $client->parq;
                    if ($parq) {
                        if (isset($request->year) && isset($request->month) && isset($request->day)) {
                            $dob = prepareDob($request->year, $request->month, $request->day);
                            $parq->dob = $dob;
                        }

                        //if(Auth::user()->hasPermission(Auth::user(), 'edit-parq')){
                        $parq->firstName = $request->first_name;
                        $parq->lastName = $request->last_name;

                        $parq->email = $request->email;
                        $parq->contactNo = $request->numb;

                        if (isset($request->gender))
                            $parq->gender = $request->gender;
                        /* else
                          $parq->gender = ''; */

                        if (isset($request->goalHealthWellness) && $request->goalHealthWellness != '') {

                            $parq->goalHealthWellness = $parq->groupValsToSingleVal($request->goalHealthWellness);
                        } else
                            $parq->goalHealthWellness = '';

                        $parq->save(); 
                    }

                    $this->entityLogin_tableRecordUpdate(['entity' => $client, 'firstName' => $request->first_name, 'lastName' => $request->last_name, 'password' => $request->clientNewPwd]);

                    if($client->save()){
                        $pass = $request->clientNewPwd;
                        if(!empty($pass))
                            User::where('account_id',$id)->update(['password'=>bcrypt($pass)]);
                    }
                    $msg['status'] = 'updated';
                }
            }
        }
        return json_encode($msg);
    }


    /**
     * client belongs to same business
     * @param void
     * @return list
    **/
    public function coClients(Request $request) {
        if (Auth::user()->business_id)
            $clients = Business::find(Auth::user()->business_id)->clients()->where('id', '!=', $request->id)->get();
        else
            return [];
        return $this->prepareClientsList($clients);
    }


    /**
     * all client which present in same business
     * @param void
     * @return list
    **/
    public function allClients(Request $request) {
        $businessId = Auth::user()->business_id;
        if ($businessId)
            $clients = Business::find(Auth::user()->business_id)->clients()->where('id', Auth::user()->account_id)->get();
        else
            return [];

        return $this->prepareClientsList($clients);
    }


    /**
     * prepare Clients List
     * @param client
     * @return list
    **/
    protected function prepareClientsList($clients) {
        $index = 0;
        $cl = array();
        foreach ($clients as $client) {
            if (Auth::user()->account_id == $client->id) {
                $cl[$index]['id'] = $client->id;
                $cl[$index]['name'] = $client->firstname . ' ' . $client->lastname;
                $cl[$index]['email'] = $client->email;
                $cl[$index]['phone'] = $client->phonenumber;
            }
            $index++;
        }
        return json_encode($cl);
    }


    /**
     * all staff list
     * @param void
     * @return list
    **/
    public function allStaffs(Request $request) {
        $businessId = Auth::user()->business_id;
        if ($businessId)
            $staffs = Business::find($businessId)->staffs;
        else
            return [];

        return $this->prepareStaffsList($staffs);
    }


    /**
     * all staff list
     * @param staff
     * @return list
    **/
    protected function prepareStaffsList($staffs) {
        $index = 0;
        $cl = array();
        foreach ($staffs as $staff) {
            $cl[$index]['id'] = $staff->id;
            $cl[$index]['name'] = $staff->first_name . ' ' . $staff->last_name;
            $cl[$index]['email'] = $staff->email;
            $cl[$index]['phone'] = $staff->phone;
            $index++;
        }
        return json_encode($cl);
    }


    /**
     * all contact
     * @param void
     * @return list
    **/
    public function allContacts(Request $request) {
        $businessId = Auth::user()->business_id;
        if ($businessId)
            $contacts = Business::find($businessId)->contacts;
        else
            return [];

        return $this->prepareContactsList($contacts);
    }


    /**
     * all contact list
     * @param void
     * @return list
    **/
    protected function prepareContactsList($contacts) {
        $index = 0;
        $cl = array();
        foreach ($contacts as $contact) {
            $cl[$index]['id'] = $contact->id;
            $cl[$index]['name'] = $contact->contact_name;
            $cl[$index]['email'] = $contact->email;
            $cl[$index]['phone'] = $contact->phone;
            $index++;
        }
        return json_encode($cl);
    }


    /**
     * update client data
     * @param data
     * @return yes/no
    **/
    public function updateField(Request $request) {
        $client = Clients::find($request->entityId);
        $parq = $client->parq;
        $clientData = array();
        $parqData = array();

        if ($request->entityProperty == 'firstName') {
            $value = $request->firstName;
            $clientData = array('firstname' => $value);
            $parqData = array('firstName' => $value);
        } else if ($request->entityProperty == 'lastName') {
            $value = $request->lastName;
            $clientData = array('lastname' => $value);
            $parqData = array('lastName' => $value);
        } else if ($request->entityProperty == 'accStatus') {
            $clientData = array('account_status' => $this->getStatusForbackend($request->accStatus));
            $value = $request->accStatus . '|' . $clientData['account_status'];
        } else if ($request->entityProperty == 'gender') {
            $value = $request->gender;
            $clientData = array('gender' => $value);
            $parqData = array('gender' => $value);
        } else if ($request->entityProperty == 'goals') {
            if ($request->goals != '')
                $value = $parq->groupValsToSingleVal($request->goals);
            else
                $value = '';

            $parqData = array('goalHealthWellness' => $value);
        }
        else if ($request->entityProperty == 'dob') {
            $value = prepareDob($request->year, $request->month, $request->day);
            $parqData = array('dob' => $value);
            if ($value)
                $parqData['age'] = '';
        }
        else if ($request->entityProperty == 'email') {
            if (!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'client', 'id' => $request->entityId])) {
                //if(!$this->ifEmailAvailable($request->email, $request->entityId)){
                return json_encode([
                    'status' => 'emailExistError',
                    'message' => 'This email is already in use'
                ]);
            }

            $value = $request->email;
            $clientData = array('email' => $value);
            $parqData = array('email' => $value);
        } else if ($request->entityProperty == 'phone') {
            $value = $request->phone;
            $clientData = array('phonenumber' => $value);
            $parqData = array('contactNo' => $value);
        } else if ($request->entityProperty == 'occupation') {
            $value = $request->occupation;
            $parqData = array('occupation' => $value);
        }

        if (count($clientData))
            $client->update($clientData);

        //if(Auth::user()->hasPermission(Auth::user(), 'edit-parq') && count($parqData))
        if (count($parqData))
            $parq->update($parqData);

        return json_encode([
            'status' => 'updated',
            'value' => $value
        ]);
    }


    /**
     * upload files
     * @param file
     * @return list
    **/
    public function uploadFile(Request $request) {
        if ($request->hasFile('fileToUpload')) {
            $file = $request->file('fileToUpload');
            $timestamp = md5(time() . rand());
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $name = $timestamp . '.' . $extension;
            $file->move(public_path() . '/uploads/', $name);
            return $name;
        }
        else if($request->hasFile('newFileToUpload'))
        {

            $file = $request->file('newFileToUpload');
            $timestamp = md5(time() . rand());
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $name = $timestamp . '.' . $extension;
            $file->move(public_path() . '/uploads/', $name);
            return response()->json($name);

        }

         else if ($request->photoName) {
            $iWidth = $request->w;
            $iHeight = $request->h;
            $uploadPath = public_path() . '/uploads/';
            $temp = explode('.', $request->photoName);
            $extension = $temp[1];
            $extension = strtolower($extension);
            $filename = $uploadPath . $request->photoName;
            $this->correctImageOrientation($filename);
            if ($extension == 'jpg' || $extension == 'jpeg')
                $vImg = @imagecreatefromjpeg($uploadPath . $request->photoName);
            else if ($extension == 'png')
                $vImg = @imagecreatefrompng($uploadPath . $request->photoName);
            else
                @unlink($uploadPath . $request->photoName);

            $vDstImg = @imagecreatetruecolor($iWidth, $iHeight);
            if ($request->widthScale && $request->widthScale != 'Infinity') {
                $x1 = (int) ($request->x1 * $request->widthScale);
                $w = (int) ($request->w * $request->widthScale);
            } else {
                $x1 = (int) $request->x1;
                $w = (int) $request->w;
            }
            if ($request->heightScale && $request->heightScale != 'Infinity') {
                $y1 = (int) ($request->y1 * $request->heightScale);
                $h = (int) ($request->h * $request->heightScale);
            } else {
                $y1 = (int) $request->y1;
                $h = (int) $request->h;
            }

            imagecopyresampled($vDstImg, $vImg, 0, 0, $x1, $y1, $iWidth, $iHeight, $w, $h);
            // imagejpeg($vDstImg, $uploadPath . 'thumb1_' . $request->photoName, 90);
            imagejpeg($vDstImg, $uploadPath . 'thumb_' . $request->photoName, 90);
            /*  */
            // $destinationPath = public_path('/uploads/'); // upload path
            //     $thumb = Image::make(public_path('/uploads/thumb1_'.$request->photoName))->resize(900, 300, function ($constraint) {
            //         $constraint->aspectRatio(); //maintain image ratio
            //     });
            // $thumb->save($destinationPath.'thumb_'.$request->photoName);
            // @unlink($uploadPath . 'thumb1_' . $request->photoName);

            /*  */
            if ($request->prePhotoName) {
                @unlink($uploadPath . $request->prePhotoName);
                @unlink($uploadPath . 'thumb_' . $request->prePhotoName);
            }

            return $request->photoName;
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
                // then rewrite the rotated image back to the disk as $filename 
                imagejpeg($img, $filename, 95);
              } // if there is some rotation necessary
            } // if have the exif orientation info
        } // if function exists 
    }


    /**
     * upload files in crm to api
     * @param file
     * @return list
    **/
    public function uploadFileCrm(Request $request) {
        if ($request->hasFile('fileToUpload')) {
            $file = $request->file('fileToUpload');
            $timestamp = md5(time() . rand());
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $name = $timestamp . '.' . $extension;
            // $file->move(public_path() . '/uploads/', $name);
            // if ($request->hasFile('fileToUpload')) {
             $filename  = $_FILES['fileToUpload']['tmp_name'];
             $handle    = fopen($filename, "r");
             $data      = fread($handle, filesize($filename));
             $POST_DATA = array(
               'file' => base64_encode($data),
               'api'  => 'API_IMAGE',
               'img_ext' => $extension
             );
             
             /*dd('http://'.crmPath().'api/photo-upload');*/
             $curl = curl_init();
             curl_setopt($curl, CURLOPT_URL, 'http://'.crmPath().'api/photo-upload');
             curl_setopt($curl, CURLOPT_TIMEOUT, 30);
             curl_setopt($curl, CURLOPT_POST, 1);
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($curl, CURLOPT_POSTFIELDS, $POST_DATA);
             $response = curl_exec($curl);
             curl_close ($curl);
             return $response;
        } else if ($request->photoName) {
            
            $POST_DATAA = array(
               'photoName' => $request->photoName,
               'widthScale'  => $request->widthScale,
               'x1' => $request->x1,
               'w' => $request->w,
               'heightScale' => $request->heightScale,
               'y1' => $request->y1,
               'h' => $request->h,
               'prePhotoName' => $request->prePhotoName,

             );
             // dd(crmPath());
             // dd('http://'.crmPath().'api/photo-upload');
             $curl = curl_init();
             curl_setopt($curl, CURLOPT_URL, 'http://'.crmPath().'api/photo-upload');
             curl_setopt($curl, CURLOPT_TIMEOUT, 30);
             curl_setopt($curl, CURLOPT_POST, 1);
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($curl, CURLOPT_POSTFIELDS, $POST_DATAA);
             $responsee = curl_exec($curl);
             // dd($responsee);
             curl_close ($curl);



            return $responsee;
        }
    }


    /**
     * distroy files 
     * @param file
     * @return response
    **/
    public function destroyFile(Request $request) {
        @unlink(public_path() . '/uploads/' . $request->photoName);
    }


    /**
     * no image 
     * @param image
     * @return src
    **/
    public function noimageSrc(Request $request) {
        return dpSrc('', $request->gender);
    }


    /**
     * save uploded file name in db
     * @param file
     * @return file name
    **/
    public function saveFile(Request $request) {
        $clientId = (int) $request->id;
        $client = Clients::find($clientId);
        if(!empty($request->cover_image)){
            $client->update(array('cover_image' => $request->photoName));
        }else{
            $client->update(array('profilepic' => $request->photoName));
        }
        
        return url('/uploads/thumb_' . $request->photoName);
    }

    /**
     * entity Login table Record Update
     * @param data
     * @return void
    **/
    protected function entityLogin_tableRecordUpdate($data) {
        $user = $data['entity']->user;
        if ($user) {
            if (array_key_exists('firstName', $data))
                $user->name = $data['firstName'];

            if (array_key_exists('lastName', $data))
                $user->last_name = $data['lastName'];


            if (array_key_exists('password', $data) && $data['password'])
                $user->password = bcrypt($data['password']);

            $user->save();
        }
    }

    /**
     * get client details
     * @param void
     * @return void
    **/

    public function clientsDetailsNew(Request $request)
    {
        
    if ($request->parameter1 == 'WeightAndDate') {
     
     if($request->parameter2 == 'Measurements')
    { 
       $before_after = MeasurementBeforeAfterImage::where('uploaded_by',Auth::User()->account_id)->orderBy('id','desc')->get();
       $gallery = MeasurementGalleryImage::where('uploaded_by',Auth::User()->account_id)->orderBy('id','desc')->paginate(10);
       $progress = FinalProgressPhoto::where('client_id',Auth::User()->account_id)->orderBy('id','desc')->get();
       return view('Result.parq.weight-date.measurements',compact('gallery','before_after','progress'));

    }
    elseif ($request->parameter2 == 'smarater') {
        return view('Result.parq.weight-date.smarter');

       
    }
    else{
       return view('Result.parq.weight-date.motivation');
    }
}
elseif ($request->parameter1 == 'train-gain') {
     
     if($request->parameter2 == 'strength-in-numbers')
    {
       return view('Result.parq.train-gain.strength-in-numbers');

    }
    elseif ($request->parameter2 == 'fitness-mapper') {
       return view('Result.parq.train-gain.fitness-mapper');

       
    }
     else {
       return view('Result.parq.train-gain.rest-in-recovery');

       
    }
    
}
elseif ($request->parameter1 == 'trace-and-replace') {
    if($request->parameter2 == 'nutritional-journal')
    {
        $activity_lavel = [];
        $weight = [];
        $prepare_own_meals = [];
        $nutritional_journal = NutritionalJournal::where('client_id',Auth::User()->account_id)->first();
        if($nutritional_journal){
            $activity_lavel = explode(',',$nutritional_journal->activity_lavel);
        }
        if($nutritional_journal){
            $weight = explode(',',$nutritional_journal->weight);
        }
        if($nutritional_journal){
            $prepare_own_meals = explode(',',$nutritional_journal->prepare_own_meals);
        }
        
        if(isset($nutritional_journal)){
            return view('Result.parq.trace-replace.progress-page',compact('nutritional_journal','activity_lavel','weight','prepare_own_meals'));
        }else{
            return view('Result.parq.trace-replace.nutritional-journal',compact('nutritional_journal','activity_lavel','weight','prepare_own_meals'));
        }
        

    }
    elseif ($request->parameter2 == 'portion-distrortion') {
       return view('Result.parq.trace-replace.portion-distrortion');

       
    }
     else {
       return view('Result.parq.trace-replace.limiting-vices');

       
    }
}
elseif ($request->parameter1 == 'diarise-and-prioritise') {
    if($request->parameter2 == 'stay-focused')
    {
       return view('Result.parq.diarise-and-prioritise.stay-focused');

    }
    elseif ($request->parameter2 == 'revise-adjust') {
       return view('Result.parq.diarise-and-prioritise.revise-adjust');

       
    }
    elseif ($request->parameter2 == 'consistency-beats') {
       return view('Result.parq.diarise-and-prioritise.consistency-beats');
    }
    elseif ($request->parameter2 == 'bucket-list') {
       return view('Result.parq.diarise-and-prioritise.bucket-list');
    }
    else{
       return view('Result.parq.diarise-and-prioritise.celebrate');
    }
    
}

    }

    public function showMeasurement(){
        return view('Result.parq.weight-date.measurement-page');
    }

    public function editNutritionalJournal(){
        $activity_lavel = [];
        $weight = [];
        $prepare_own_meals = [];
        $nutritional_journal = NutritionalJournal::where('client_id',Auth::User()->account_id)->first();
        if($nutritional_journal){
            $activity_lavel = explode(',',$nutritional_journal->activity_lavel);
        }
        if($nutritional_journal){
            $weight = explode(',',$nutritional_journal->weight);
        }
        if($nutritional_journal){
            $prepare_own_meals = explode(',',$nutritional_journal->prepare_own_meals);
        }

        return view('Result.parq.trace-replace.nutritional-journal',compact('nutritional_journal','activity_lavel','weight','prepare_own_meals'));
        
    }

   public function storeNutritionalJournal(Request $request){
    //    dd($request->all());
        $update = NutritionalJournal::where('client_id',Auth::User()->account_id)->first();
        $activity_lavel = '';
        $weight = '';
        $prepare_own_meals = '';
        if(count($request->activity_lavel) > 0){
            $activity_lavel = implode(',',$request->activity_lavel);
        }
        if(count($request->weight) > 0){
            $weight = implode(',',$request->weight);
        }
        if(count($request->prepare_own_meals) > 0){
            $prepare_own_meals = implode(',',$request->prepare_own_meals);
        }

        $request->merge([
            'client_id' => Auth::User()->account_id,
            'activity_lavel' => $activity_lavel,
            'weight' => $weight,
            'prepare_own_meals' => $prepare_own_meals,

        ]);
        if($update){
            $update = $update->update($request->all());
        }else{
            $save = NutritionalJournal::create($request->all());
        }
        
        return redirect('epic/trace-and-replace/nutritional-journal');
   } 

   public function clientsDetails(Request $request) {




        $clientSelectedMenus = [];
        if(Auth::user()->account_type == 'Client') {
            $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
 
            if(!in_array('parq', $clientSelectedMenus))
              return redirect('access-restricted');
        }  
        $id = $request->id;
        $businessId = Auth::user()->business_id;
        $clientId = Auth::user()->account_id;
        $countries = \Country::getCountryLists();
        $currencies = \Currency::$currencies;
        $timezones = \TimeZone::getTimeZone();

        $clients = Clients::with('parq')->find($clientId);
        
        $parq = $clients->parq;

        
        if ($parq->dob != '0000-00-00') {
            $carbonDob = Carbon::createFromFormat('Y-m-d', $parq->dob);
            $overviewDob = $carbonDob->format('d M, Y');
            $parq->birthYear = $carbonDob->year;
            $parq->birthMonth = $carbonDob->month;
            $parq->birthDay = $carbonDob->day;
        } else
            $overviewDob = $parq->birthYear = $parq->birthMonth = $parq->birthDay = '';

            //dd($parq->referralNetwork);
            if($parq->referralNetwork == 'Client'){ 
                  $P_clients = Clients::withTrashed()->find($parq->referralId);
                  if($P_clients != null){
                  $parq->clientName = $P_clients->firstname.' '.$P_clients->lastname;
                  $parq->clientId = $parq->referralId;
                  if($P_clients->trashed())
                  $parq->isReferenceDeleted = true;
                  }
              }
              else if($parq->referralNetwork == 'Staff'){
                  $staff = Staff::withTrashed()->find($parq->referralId);
                  if($staff != null){
                  $parq->staffName = $staff->first_name.' '.$staff->last_name;
                  $parq->staffId = $parq->referralId;
                  if($staff->trashed())
                  $parq->isReferenceDeleted = true;
                  }
              }
              else if($parq->referralNetwork == 'Professional network'){
                  $contact = Contact::withTrashed()->find($parq->referralId);
                  if($contact != null){
                  $parq->proName = $contact->contact_name;
                  $parq->proId = $parq->referralId;
                  if($contact->trashed())
                  $parq->isReferenceDeleted = true;
                  }
              } 




        if ($parq->addrState)
            $parq->stateName = \Country::getStateName($parq->country, $parq->addrState);      
        
        $parq->paIntensity = explode(',', $parq->paIntensity);

        $parq->headInjury = isset($parq->headInjury)&& $parq->headInjury != '' ? explode(',', $parq->headInjury):[];
        $parq->neckInjury = isset($parq->neckInjury)&& $parq->neckInjury !='' ?explode(',', $parq->neckInjury):[];
        $parq->shoulderInjury = isset($parq->shoulderInjury)&& $parq->shoulderInjury !='' ?explode(',', $parq->shoulderInjury):[];
        $parq->armInjury = isset($parq->armInjury)&& $parq->armInjury !='' ?explode(',', $parq->armInjury):[];
        $parq->handInjury = isset($parq->handInjury)&& $parq->handInjury !='' ?explode(',', $parq->handInjury):[];
        $parq->backInjury = isset($parq->backInjury)&& $parq->backInjury !='' ?explode(',', $parq->backInjury):[];
        $parq->hipInjury = isset($parq->hipInjury)&& $parq->hipInjury !='' ?explode(',', $parq->hipInjury):[];
        $parq->legInjury = isset($parq->legInjury)&& $parq->legInjury !='' ?explode(',', $parq->legInjury):[];
        $parq->footInjury = isset($parq->footInjury)&& $parq->footInjury !='' ?explode(',', $parq->footInjury):[];
        $parq->goalHealthWellnessRaw = $parq->goalHealthWellness;
        $parq->goalHealthWellness = explode(',', $parq->goalHealthWellness);

        $parq->headImprove = isset($parq->headImprove)&& $parq->headImprove !='' ?explode(',', $parq->headImprove):[];
        $parq->neckImprove = isset($parq->neckImprove)&& $parq->neckImprove !='' ?explode(',', $parq->neckImprove):[];
        $parq->footImprove = isset($parq->footImprove)&& $parq->footImprove !='' ?explode(',', $parq->footImprove):[];
        $parq->legImprove = isset($parq->legImprove)&& $parq->legImprove !='' ?explode(',', $parq->legImprove):[];
        $parq->handImprove = isset($parq->handImprove)&& $parq->handImprove !='' ?explode(',', $parq->handImprove):[];
        $parq->backImprove = isset($parq->backImprove)&& $parq->backImprove !='' ?explode(',', $parq->backImprove):[];
        $parq->hipImprove = isset($parq->hipImprove)&& $parq->hipImprove !='' ?explode(',', $parq->hipImprove):[];
        $parq->hamstringsImprove = isset($parq->hamstringsImprove)&& $parq->hamstringsImprove !='' ?explode(',', $parq->hamstringsImprove):[];
        $parq->shouldersImprove = isset($parq->shouldersImprove)&& $parq->shouldersImprove !='' ?explode(',', $parq->shouldersImprove):[];
        $parq->armsImprove = isset($parq->armsImprove)&& $parq->armsImprove !='' ?explode(',', $parq->armsImprove):[];
        $parq->calvesImprove = isset($parq->calvesImprove)&& $parq->calvesImprove !='' ?explode(',', $parq->calvesImprove):[];
        $parq->quadsImprove = isset($parq->quadsImprove)&& $parq->quadsImprove !='' ?explode(',', $parq->quadsImprove):[];

       
        $parq->quadsImprove = isset($parq->quadsImprove)&& $parq->quadsImprove !='' ?explode(',', $parq->quadsImprove):[];
        
        $parq->lifestyleImprove = explode(',', $parq->lifestyleImprove);
        $parq->goalWantTobe = explode(',', $parq->goalWantTobe);
        $parq->goalWantfeel = explode(',', $parq->goalWantfeel);
        $parq->goalWantHave = explode(',', $parq->goalWantHave);
        $parq->motivationImprove = explode(',', $parq->motivationImprove);
        if($parq->heightUnit =='Imperial'){
            $parq->height;
        }
        else{
            $parq->weight =number_format($parq->weight, 1);
        }
        
        // if(!empty($parq->hearUs))
        // {
        //     if($parq->hearUs == 'onlinesocial')
        //     {
        //         $parq->hearUs = 'Online & Social Media';
        //     }
        //     if($parq->hearUs == 'mediapromotions')
        //     {
        //         $parq->hearUs = 'Media & Promotions';
        //     }

        // }

//        return view('profile_details', compact('overviewDob', 'clients', 'parq', 'countries', 'timezones', 'currencies'));

        // dd($parq->hearUs);
   if($request->parameter1 == 'AssessAndProgress')
   {
    if($request->parameter2 == 'PersonalDetails')
    {
        $id = 0;
        $view = 'Result.parq-step1';
    }
    else if($request->parameter2 == 'ExercisePreference')
    {
        $id = 1;
        $view = 'Result.parq-step2';
    }
    else if($request->parameter2 == 'IllnessAndInjury')
    {
        $id = 2;
        $view = 'Result.parq-step3';
    }
    else if($request->parameter2 == 'PARQ')
    {
        $id = 3;
        $view = 'Result.parq-step4';
    }
    else {
        $id = 4;
        $view = 'Result.parq-step5';
    }

}

        // if ($parq->state != 'completed' && $parq->client_waiver_term == 1) { 
        if ($parq->state == 'completed' && $parq->client_waiver_term == 1) { 
            $parq->preferredTraingDays = json_decode($parq->preferredTraingDays, 1);

            return view('Result.parq.view.assess_progress', compact('overviewDob', 'countries', 'timezones', 'currencies', 'clients', 'parq','id'));
        }
          else
        {
           
             return view($view, compact('overviewDob', 'clients', 'parq', 'countries', 'timezones', 'currencies','id'));
        }
    }



    /**
     * get benchmark details
     * @param void
     * @return benchmark view
    **/
    public function benchmarkDetails() {
        $businessId = Auth::user()->business_id;
        $clientId = Auth::user()->account_id;
        $clients = Clients::with('benchmarks')->find($clientId);
        $benchmarks = $clients->benchmarks;
        return view('Result.benchmark.view.benchmark', compact('clients', 'benchmarks'));
    }

    /**
     * get makeup count
     * @param eventCollections
     * @return mekup count
    **/
    protected function getMakeUpCount($eventCollections) {
        return $eventCollections->filter(function($event) {
                    $model = class_basename($event);
                    return ($model == 'StaffEventClass' && $event->pivot->secc_if_make_up === 1 && $event->pivot->secc_if_make_up_created !== 1 );
                })->count();
    }

    /**
     * showFinancialTools
     * @param data
     * @return financial tool
    **/
    public function showFinancialTools(){
        $businessId = auth()->user()->business_id;
        $financeData = FinanaceTool::where('business_id','=',$businessId)->first();
        $settingPrefData = FtTaxPreference::where('tax_type','=','gst')->first();
        $timeframe = FtTaxPreference::first();
        return view('Result.financialtool.index',compact('financeData','settingPrefData','timeframe'));
    }

    /**
     * showFinancialTools1
     * @param data
     * @return financial tool
    **/
    public function showFinancialTools1(){
        $businessId = auth()->user()->business_id;
        $financeData = FtSetup::where('business_id','=',$businessId)->first();
        $settingPrefData = FtTaxPreference::where('tax_type','=','gst')->first();
        $timeframe = FtTaxPreference::first();
        $ftPartnership = FtPartnership::where('business_id','=',$businessId)->get();
        $ftPartnershipData['profit_percentage'] = [];
        $ftPartnershipData['invested_amount'] = [];
        $ftPartnershipData['excl_gst'] = [];
        $ftPartnershipData['gst_paid'] = [];
        foreach($ftPartnership as $key => $partnership)
        {
           $ftPartnershipData['profit_percentage'][] = $partnership->profit_percentage;
           $ftPartnershipData['invested_amount'][] = $partnership->invested_amount;
           $ftPartnershipData['excl_gst'][] = $partnership->excl_gst;
           $ftPartnershipData['gst_paid'][] = $partnership->gst_paid;
        }
        return view('Result.financialtoolnew.index',compact('financeData','settingPrefData','timeframe','ftPartnershipData'));
    }


    /**
     * save Expenses
     * @param data
     * @return financial tool
    **/
    public function saveExpenses(Request $request){
        $inputs['tax_type'] = ($request->tax_type) ? $request->tax_type : '';
        $inputs['is_gst_registered'] = $request->is_gst_registered == '1' ? 1 : 0;
        $inputs['gst_no'] = isset($request->gst_no) ? $request->gst_no : '';
        $inputs['gst_percentage'] = isset($request->gst_percentage) ? $request->gst_percentage : 0;
        $inputs['setup_expenses'] = $request->setup_expenses;
        $inputs['setup_exp_calculated'] = ($request->setup_exp_calculated) ? $request->setup_exp_calculated : 0;
        $inputs['setup_exp_est'] = $request->setup_exp_est;
        $inputs['setup_exp_gst_incl'] = $request->setup_exp_gst_incl;
        // $inputs['setup_exp_gst_excl'] = $request->setup_exp_gst_excl;
        $inputs['business_expenses'] = $request->business_expenses;
        $inputs['business_exp_calculated'] = ($request->business_exp_calculated) ? $request->business_exp_calculated : 0;
        $inputs['business_exp_est'] = $request->business_exp_est;
        $inputs['business_exp_gst_incl'] = $request->business_exp_gst_incl;
        // $inputs['business_exp_gst_excl'] = $request->business_exp_gst_excl;
        $inputs['living_expenses'] = $request->living_expenses;
        $inputs['living_exp_calculated'] = ($request->living_exp_calculated) ? $request->living_exp_calculated : 0;
        $inputs['living_exp_est'] = $request->living_exp_est;
        $inputs['living_exp_gst_incl'] = $request->living_exp_gst_incl;
        // $inputs['living_exp_gst_excl'] = $request->living_exp_gst_excl;
        $inputs['partnership_expenses'] = $request->partnership_expenses;
        $inputs['business_id']= !empty(auth()->user()->business_id) ? auth()->user()->business_id : 0;

        // check if exist, update otherwise
        $isFinanceExists = FinanaceTool::where('business_id','=',$inputs['business_id'])->first();
        if(!empty($isFinanceExists)){
            if ($isFinanceExists->update($inputs)) {
                return response()->json([ 'status' => 200, 'msg' => 'Expenses saved successfully!',
                                          'data'=>['id'=>$isFinanceExists->id]], 200);
            }
        }
        // create if new
        $finance = FinanaceTool::create($inputs);
        if (!empty($finance->id)) {
            return response()->json([ 'status' => 200, 'msg' => 'Expenses saved successfully!',
                                      'data'=>['id'=>$finance->id]], 200);
        }
        return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
    }


    /**
     * save Business Structure
     * @param data
     * @return response
    **/
    public function saveBusinessStructure(Request $request){
	    $inputs['business_type'] = ($request->business_type) ? $request->business_type : '';
	    $inputs['is_gst_registered'] = $request->is_gst_registered == '1' ? 1 : 0;
        $inputs['gst_no'] = isset($request->gst_no) ? $request->gst_no : '';
	    $inputs['gst_percentage'] = isset($request->gst_percentage) ? $request->gst_percentage : 0;
    	$inputs['business_id']= !empty(auth()->user()->business_id) ? auth()->user()->business_id : 0;

	    // check if exist, update otherwise
    	$isFinanceExists = FtSetup::where('business_id','=',$inputs['business_id'])->first();
        if(!empty($isFinanceExists)){
	        if ($isFinanceExists->update($inputs)) {
	        	return response()->json([ 'status' => 200, 'msg' => 'Expenses saved successfully!',
		                                  'data'=>['id'=>$isFinanceExists->id]], 200);
	        }
        }
        // create if new
    	$finance = FtSetup::create($inputs);
	    if (!empty($finance->id)) {
	    	return response()->json([ 'status' => 200, 'msg' => 'Expenses saved successfully!',
		                              'data'=>['id'=>$finance->id]], 200);
	    }
	    return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
    }


    /**
     * save Setup Exp
     * @param data
     * @return response
    **/
	public function saveSetupExp(Request $request,$id){
		if(empty($id)){
			$id = !empty(auth()->user()->business_id) ? auth()->user()->business_id : 0;
		}
		$finance = FtSetup::where('business_id','=',$id)->first();
		if(empty($finance)){
			return response()->json([ 'status' => 404, 'msg'=> 'Record not found!','data'=>null], 200);
		}
		// FIelds 
        $inputs['setup_expenses'] = $request->setup_expenses;
        $inputs['se_calculated'] =  $request->se_calculated;
        $inputs['se_gst_excl'] =  $request->se_gst_excl;
        $inputs['se_gst_paid'] =  $request->se_gst_paid;
        $inputs['se_est_capital'] =  $request->se_est_capital;
        $inputs['se_add_capital_req'] =  $request->se_add_capital_req;
        $inputs['se_repayment_monthly_period_capital_req'] =  $request->se_repayment_monthly_period_capital_req;
        $inputs['se_interest_rate_fr_loan'] =  $request->se_interest_rate_fr_loan;
        $inputs['se_interest'] =  $request->se_interest;
        $inputs['se_total'] =  $request->se_total;
        $inputs['se_monthly_repayment'] =  $request->se_monthly_repayment;
		if ($finance->update($inputs)) {
			return response()->json([ 'status' => 200, 'msg' => 'saveSetupExp saved successfully!',
			                          'data'=>['id'=>$finance->business_id]], 200);
		}
		return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
	}


    /**
     * save Operation Exp
     * @param data
     * @return response
    **/
	public function saveOperationExp(Request $request,$id){
		if(empty($id)){
			$id = !empty(auth()->user()->business_id)? auth()->user()->business_id : 0;
		}
		$finance = FtSetup::where('business_id','=',$id)->first();
		if(empty($finance)){
			return response()->json([ 'status' => 404, 'msg'=> 'Record not found!','data'=>null], 200);
		}
        // Fields
        $inputs['business_expenses'] = $request->business_expenses;
        $inputs['be_calculated'] = $request->be_calculated;
        $inputs['be_gst_excl'] = $request->be_gst_excl;
        $inputs['be_gst_paid'] = $request->be_gst_paid;
        $inputs['be_loan_repayment_amt'] = $request->be_loan_repayment_amt;
        $inputs['be_total_exp_per_mnth'] = $request->be_total_exp_per_mnth;
        $inputs['be_profit_req_after_tax'] = $request->be_profit_req_after_tax;
        $inputs['be_tax_payable'] = $request->be_tax_payable;
        $inputs['be_gst_paid_per_annum'] = $request->be_gst_paid_per_annum;
        $inputs['be_total_exp_per_annum'] = $request->be_total_exp_per_annum;
        $inputs['be_profit_after_tax'] = $request->be_profit_after_tax;

        // living_expense
        $inputs['living_expenses'] = $request->living_expenses;
        $inputs['le_calculated']   =   $request->le_calculated;
        $inputs['le_gst_excl']     =   $request->le_gst_excl;
        $inputs['le_gst_paid']     =   $request->le_gst_paid;

        // Partnership
        $inputs['partnership_expenses'] = $request->partnership_expenses;
        $partnership_total = 0;
        if(!empty($request->profit_percentage) && $request->business_type == 'partnership') {// If partnership only{
            if(FtPartnership::where('business_id',auth()->user()->business_id)->count() > 0){
                FtPartnership::where('business_id',auth()->user()->business_id)->delete();
            }
            foreach($request->profit_percentage as $key => $val){
                $data['business_id'] = !empty(auth()->user()->business_id) ? auth()->user()->business_id : 0;
                $data['profit_percentage'] = $val;
                $data['invested_amount'] = $request->invested_amount[$key];
                $data['excl_gst'] = $request->gst_excl[$key];
                $data['gst_paid'] = $request->gst_paid[$key];
                $partnership_total +=  $request->invested_amount[$key];
                FtPartnership::create($data);
            }
        }
        
        $inputs['partnership_total'] = $partnership_total;
        $inputs['no_of_partner'] = $request->no_of_partner;

	    if ($finance->update($inputs)) {
	    	return response()->json([ 'status' => 200, 'msg' => 'saveOperationExp successfully!',
		                              'data'=>['id'=>$finance->business_id]], 200);
	    }
		return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
	}


    /**
     * save Sale Projection
     * @param data
     * @return response
    **/
	public function saveSaleProjection(Request $request,$id){
		if(empty($id)){
			$id = !empty(auth()->user()->business_id)? auth()->user()->business_id : 0;
		}
		$finance = FtSetup::where('business_id','=',$id)->first();
		if(empty($finance)){
			return response()->json([ 'status' => 404, 'msg'=> 'Record not found!','data'=>null], 200);
		}
		
        // Session Required section 
        $inputs['session_rate']   = $request->session_rate;
        $inputs['session_req'] = $request->session_req;
        $inputs['ann_working_weeks'] = $request->ann_working_weeks;
        $inputs['weekly_sess_req'] = $request->weekly_sess_req;
        $inputs['no_of_clients'] = $request->no_of_clients;
        $inputs['session_spots_req'] = $request->session_spots_req;
        $inputs['avg_sess_pweek_pclient'] = $request->avg_sess_pweek_pclient;
        $inputs['clients_req_to_break_even'] = $request->clients_req_to_break_even;
        $inputs['average_client_spend'] = $request->average_client_spend;
        $inputs['estimated_client_cap'] = $request->estimated_client_cap;

        // Client Required section 
        $inputs['client_active_current'] = $request->client_active_current;
        $inputs['session_req_per_week'] = $request->session_req_per_week;
        $inputs['clients_req_6_per_session'] = $request->clients_req_6_per_session;
        $inputs['timeframe_capicity'] = $request->timeframe_capicity;
        $inputs['projected_cons_conv_rate'] = $request->projected_cons_conv_rate;
        $inputs['consultations_req'] = $request->consultations_req;
        $inputs['contact_conv_rate'] = $request->contact_conv_rate;
        $inputs['leads_req'] = $request->leads_req;
        $inputs['avg_lead_gen_req_per_week_fr_52'] = $request->avg_lead_gen_req_per_week_fr_52;
        $inputs['avg_consult_week'] = $request->avg_consult_week;
        $inputs['avg_singed_client_week'] = $request->avg_singed_client_week;

	    if ($finance->update($inputs)) {
	    	return response()->json([ 'status' => 200, 'msg' => 'saveSaleProjection saved successfully!',
		                              'data'=>['id'=>$finance->business_id]], 200);
	    }
	    return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
	}

	
    /**
     * show Settings And Preferences
     * @param data
     * @return response
    **/
	public function showSettingsAndPreferences(){
        $ftPref = FtTaxPreference::all();
		$timeFrame = FtTaxPreference::first();
		$companyTaxes = collect($ftPref)->filter(function ($value, $key) {
			return $value->tax_category == 'company';
		});
		$partnershipTaxes = collect($ftPref)->filter(function ($value, $key) {
			return $value->tax_category == 'partnership';
		});
		$soleTraderTaxes = collect($ftPref)->filter(function ($value, $key) {
            return $value->tax_category == 'sole-trader';
        });
        $gstTaxes = collect($ftPref)->filter(function ($value, $key) {
			return $value->tax_category == 'gst';
		});
		return view('Result.financialtool.settings-and-preferences.index',
			compact(['companyTaxes','partnershipTaxes','soleTraderTaxes','gstTaxes','timeFrame']));
	}


    /**
     * save Settings And Preferences
     * @param data
     * @return response
    **/
	public function saveSettingsAndPreferences(Request $request){
		$inputs = $request->only('tax_category','tax_type','tax_code','tax_amount','country');
		$inputs['tax_name'] = $request->tax_type == 'other' ? $request->tax_name : null;
		try{
			$taxPreference = FtTaxPreference::create($inputs);
            
			if (!empty($request->income_tax)) {
				$income_tax = [];
				foreach ($request->income_tax as $incomeTax) {
					$income_tax[] = [
						'tax_preference_id' => $taxPreference->id,
						'from_amount'       => !empty($incomeTax['cfrom_amount']) ? $incomeTax['cfrom_amount'] : 0,
						'to_amount'         => !empty($incomeTax['cto_amount']) ? $incomeTax['cto_amount'] : 0,
						'tax_percentages'   => !empty($incomeTax['ctax_percentages']) ? $incomeTax['ctax_percentages'] : 0
					];
				}
				if(!FtTaxSlab::insert($income_tax)){
					return response()->json(['status'=>'error','msg'=>'Whoops! Something went wrong.']);
				}
			}
			if(!$taxPreference->id){
				return response()->json(['status'=>'error','msg'=>'Whoops! Something went wrong.']);
			}
			$msg = !empty($inputs['tax_category']) ? ucfirst($inputs['tax_category']).' Tax saved successfully!'
				: 'Tax saved successfully!';
			return response()->json(['status'=>'success','msg'=>$msg]);
		}
		catch (\Exception $e){
			return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
		}
	}


    /**
     * edit Settings And Preferences
     * @param data
     * @return response
    **/
    public function editSettingsAndPreferences($id){
        try{
            $taxPreference = FtTaxPreference::with('slabs')
                ->where('id','=',$id)->firstOrFail();
            return response()->json(['status'=>'success','data'=>$taxPreference]);
        }
        catch (\Exception $e){
            return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
        }
    }


    /**
     * update Settings And Preferences
     * @param data
     * @return response
    **/
	public function updateSettingsAndPreferences($id,Request $request){
		try{
			$taxPreference = FtTaxPreference::where('id','=',$id)->firstOrFail();

			$inputs = $request->only('tax_category','tax_type','tax_code','tax_amount','country');
			$inputs['tax_name'] = $request->tax_type == 'other' ? $request->tax_name : null;

			if(!$taxPreference->update($inputs)){
                return response()->json(['status'=>'error','msg'=>'Whoops! Something went wrong.']);
            }

            if (!empty($request->income_tax)) {
				$income_tax_new = [];
				$update_slabs = [];
				foreach ($request->income_tax as $incomeTax) {
					if($incomeTax['slab_id'] == 0){
						$income_tax_new[] = [
							'tax_preference_id' => $taxPreference->id,
							'from_amount'       => !empty($incomeTax['cfrom_amount']) ? $incomeTax['cfrom_amount'] : 0,
							'to_amount'         => !empty($incomeTax['cto_amount']) ? $incomeTax['cto_amount'] : 0,
							'tax_percentages'   => !empty($incomeTax['ctax_percentages']) ? $incomeTax['ctax_percentages'] : 0
						];
					}else{
						$update_slabs[] = $incomeTax['slab_id'];
						$update = FtTaxSlab::where('id','=',$incomeTax['slab_id'])->update([
							'tax_preference_id' => $taxPreference->id,
							'from_amount'       => !empty($incomeTax['cfrom_amount']) ? $incomeTax['cfrom_amount'] : 0,
							'to_amount'         => !empty($incomeTax['cto_amount']) ? $incomeTax['cto_amount'] : 0,
							'tax_percentages'   => !empty($incomeTax['ctax_percentages']) ? $incomeTax['ctax_percentages'] : 0
						]);
					}
				}
				FtTaxSlab::whereNotIn('id',$update_slabs)->where('tax_preference_id','=',$id)->delete();
				if(!FtTaxSlab::insert($income_tax_new)){
					return response()->json(['status'=>'error','msg'=>'Whoops! Something went wrong.']);
				}
			}
			$msg = !empty($inputs['tax_category']) ? ucfirst($inputs['tax_category']).' Tax updated successfully!'
				: 'Tax updated successfully!';
			return response()->json(['status'=>'success','msg'=>$msg]);
		}
		catch (\Exception $e){
			return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
		}
	}


    /**
     * delete Settings And Preferences
     * @param data
     * @return response
    **/
	public function deleteSettingsAndPreferences($id){
		try{
			$taxPreference = FtTaxPreference::where('id','=',$id)->firstOrFail();
			FtTaxSlab::where('tax_preference_id','=',$id)->delete();
			if(!$taxPreference->delete()){
				return response()->json(['status'=>'error','msg'=>'Record not deleted!']);
			}
		}
		catch (\Exception $e){
			return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
		}
		return response()->json(['status'=>'success','msg'=>'Record deleted successfully']);
	}


    /**
     * clone Tax
     * @param id
     * @return response
    **/
    public function cloneTax($id) {
        try{
            $taxPreference = FtTaxPreference::where('id','=',$id)->with('slabs')->firstOrFail();
            if(!$taxPreference){
                return response()->json(['status'=>'error','msg'=>'Record not found!']);
            }
            $inputs = [];
            if(!empty($taxPreference->tax_category)) {
                if($taxPreference->tax_category == 'sole-trader') {
                    $inputs['tax_category'] = 'partnership';
                } elseif($taxPreference->tax_category == 'partnership') {
                    $inputs['tax_category'] = 'sole-trader';
                }
            }

            $inputs['tax_type'] = $taxPreference->tax_type;
            $inputs['tax_amount'] = $taxPreference->tax_amount;
            $inputs['tax_code'] = $taxPreference->tax_code;
            $inputs['tax_name'] = $taxPreference->tax_name;
            $inputs['country'] = $taxPreference->country;

            $cloneFtTaxPreference = FtTaxPreference::create($inputs);
            
            // clone tax slab
            $slabInput = [];
            if(!empty($taxPreference->slabs)) {
                foreach ($taxPreference->slabs as $key => $taxSlab) {
                    $slabInput["tax_preference_id"] = $cloneFtTaxPreference->id; 
                    $slabInput["from_amount"] = $taxSlab->from_amount;
                    $slabInput["to_amount"] = $taxSlab->to_amount;
                    $slabInput["tax_percentages"] = $taxSlab->tax_percentages;
                    FtTaxSlab::create($slabInput);
                }
            }
        }
        catch (\Exception $e){
            return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>'success','msg'=>'Record clone successfully']);
    }


    /**
     * get Slab
     * @param id
     * @return response
    **/    
    public function getSlab(Request $request){
        try{
           $amount = $request->amount;
           $tax_category = $request->tax_category;

           $taxPreference = FtTaxPreference::where('tax_category',$tax_category)->first();
           if(!$taxPreference){
                return response()->json(['status'=>'success','amount'=> 0 , 'tax_amount' => 0]);
           }

           $ftTaxSlabs  = FtTaxSlab::where('tax_preference_id',$taxPreference->id)->get();

           $taxes = [];
           foreach($ftTaxSlabs as $key => $ftTaxSlab) {
            $taxes[] = [
                         'lower' => $ftTaxSlab->from_amount ,
                         'upper' => $ftTaxSlab->to_amount ,
                         'tax' => $ftTaxSlab->tax_percentages
                       ];
           }

           $band_top = [];
           $band_rate = [];

            foreach($taxes as $key => $tax){
                $band_top[$key+1] = $tax['upper'];
                $band_rate[$key+1] = $tax['tax'] / 100;
            }

            $band_top_new = $band_top;

            array_pop($band_top_new);

            $final = FtTaxSlab::finalCalculations($band_top_new, $band_rate, $amount);

            $total_tax_paid = 0;

            if(count($final) > 0){
                foreach($final as $tax){
                    $total_tax_paid += $tax;
                }
            }

            $gstInclAmount = $amount + $total_tax_paid;

            $total_tax_paid = number_format($total_tax_paid , 2 , '.' , '');
            $gstInclAmount = number_format($gstInclAmount , 2 , '.' , '');
            return response()->json(['status'=>'success','amount'=>$gstInclAmount , 'tax_amount' => $total_tax_paid]);
        }
        catch (\Exception $e){
            return response()->json(['status'=>'error','msg'=> $e->getMessage()]);
        }
    }

    /**
     * ajax Delete Data
     * @param void
     * @return response
    **/    
    public function ajaxDeleteData(){
        try {
            FtSetup::truncate();
            FtPartnership::truncate();
            return response()->json(['status'=>'success','msg'=> 'Financial Setup data deleted']);
        } catch (\Exception $e) {
            return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
        }
    }

    /**
     * ajaxupdate Financial Time Frame
     * @param void
     * @return response
    **/   
    public function ajaxupdateFinancialTimeFrame(Request $request){
        try{
            /* update All Financial Time Frame */
            if(!\DB::table('ft_tax_preferences')->update(['financial_time_frame' => $request->financial_time_frame])){
                return response()->json(['status'=>'error','msg'=>'Whoops! Something went wrong.']);
            }
            return response()->json(['status'=>'success', 'msg'=> ''.ucfirst($request->financial_time_frame).' Financial Timeframe set successfully'], 200);
        }catch(\Exception $e) {
            return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
        }
    }


    /**
     * Create Email
     * @param void
     * @return create email view
    **/
    public function create_email(){
        return view('Result.create_email');
    }
    

    /**
     * Save Email
     * @param name and email
     * @return response
    **/
    public function save_email(Request $request){
        $name =  $request->name;
        $email = $request->email;
        $your_installation_url = 'http://emails.epicfit.net'; 
        $list = 'Ek8vr2uEjUqwunLhOGkKLA';

         $postdata = http_build_query(
            array(
            'name' => $name,
            'email' => $email,
            'list' => $list,
            'boolean' => 'true'
            )
         );
        
        $opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
        $context  = stream_context_create($opts);
        $result = file_get_contents($your_installation_url.'/subscribe', false, $context);
        
        return $result;
    }


    /**
     * Unsubscribe Email
     * @param name and email
     * @return response
    **/
    public function unsubscribe_email(Request $request){
        $name =  $request->name;
        $email = $request->email;
        $your_installation_url = 'http://emails.epicfit.net'; 
        $list = 'Ek8vr2uEjUqwunLhOGkKLA';

         $postdata = http_build_query(
            array(
            'name' => $name,
            'email' => $email,
            'list' => $list,
            'boolean' => 'true'
            )
         );
        
        $opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
        $context  = stream_context_create($opts);
        $result = file_get_contents($your_installation_url.'/unsubscribe', false, $context);

        return $result;
    }
    

    /**
     * Sending Email
     * @param name and email
     * @return response
    **/
    public function sending_email(Request $request){
        $your_installation_url = 'http://emails.epicfit.net'; 
        $list = 'Ek8vr2uEjUqwunLhOGkKLA';

         $postdata = http_build_query(
            array(
            'api_key' => 'H37CmHS0zfAeabPuH7Do',
            'from_name' => 'Epictrainer',
            'from_email' => 'info@epictrainer.com',
            'reply_to' => 'info@epictrainer.com',
            'title' =>'ABC',
            'subject' =>'Test Email',
            'html_text' =>'hjdsmxzhjdsuiewiu xz mc',
            'list_ids' =>'Ek8vr2uEjUqwunLhOGkKLA',
            'send_campaign' =>'1'
            
            )
         );
        
        $opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
        $context  = stream_context_create($opts);
        $result = file_get_contents($your_installation_url.'/api/campaigns/create.php', false, $context);

        return $result;
    }
}
