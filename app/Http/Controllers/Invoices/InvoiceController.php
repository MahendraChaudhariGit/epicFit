<?php
namespace App\Http\Controllers\Invoices;
use App\Http\Controllers\Controller;
use Auth;
use App;
use PDF;
use View;
use Mail;
use DB;
use App\InvoiceEmailLog;
use Illuminate\Http\Request;
use Session;
use App\Business;
use App\LocationArea;
use App\Location;
use App\Staff;
use App\StaffEventSingleService;
use App\StaffEventClass;
use App\StaffEventService;
use App\Invoice;
use App\Payment;
use App\InvoiceItems;
use App\Service;
use App\Makeup;
use App\Http\Traits\LocationAreaTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\SalesToolsInvoice;
use App\SalesToolsInvoicePaymentTypes;
use App\MemberShipTax;
use App\SalesToolsDiscount;
use Input;
use App\Http\Traits\InvoiceTrait;
use App\ClientMenu;
use Illuminate\Support\Facades\Response;

class InvoiceController extends Controller{
  	use LocationAreaTrait, HelperTrait, InvoiceTrait;

  	private $cookieSlug = 'invoice';

  	/**
  	 * rander invoice list
  	 * @param void
  	 * @return index blade 
  	 */ 
  	public function index(Request $request){
	  	if(!Auth::user()->hasPermission(Auth::user(), 'view-invoices'))
		  	abort(404);
		  
		/* Set url in session */
		setPrevousUrl(url()->full());

		$search = $request->get('search');
		$searchArr  = explode(' ', $search);
		
		$length = $this->getTableLengthFromCookie($this->cookieSlug);

	  	$totalAmount = Invoice::where('inv_business_id',Session::get('businessId'))->where(function($q){
			$q->where('inv_client_id','<>',0)
			->orWhere('inv_client_name','!=',null);
		})->sum('inv_total');
	  	$totalPaid = Invoice::where('inv_business_id',Session::get('businessId'))->where(function($q){
			$q->where('inv_client_id','<>',0)
			->orWhere('inv_client_name','!=',null);
		})->where('inv_status', 'Paid')->sum('inv_total');

	  	$cookieName = 'invoice-list-status-filter';
	  	if(isset($_COOKIE[$cookieName]))
        	$status = $_COOKIE[$cookieName];
        else
        	$status = '';

        if(isset($_COOKIE['dueEndDate']) && isset($_COOKIE['dueSatrtDate'])){
        	$startDueDate = $_COOKIE['dueSatrtDate'];
        	$endDueDate = $_COOKIE['dueEndDate'];
        }
        else{
        	$startDueDate = 'null';
        	$endDueDate = 'null';
        }

	  	$query = Invoice::where('inv_business_id',Session::get('businessId'))->where(function($q){
			$q->where('inv_client_id','<>',0)
			->orWhere('inv_client_name','!=',null);
		});
	  	if($startDueDate != 'null' && $endDueDate != 'null')
	  		$query->where('inv_due_date','>=', $startDueDate)->where('inv_due_date','<=',$endDueDate);
	  	if($status)
	  		$query->where('inv_status', $status);
	  	if($search && count($searchArr)){
	  		$query->where(function($query) use($search, $searchArr){
                        // $query->whereHas('client', function($query) use ($search){
                        //       		$query->orWhere('firstname', 'like', "%$search%")
                        //       			  ->orWhere('lastname', 'like', "%$search%");
						//       })
						$query->whereHas('client', function($query) use ($search, $searchArr){
							// dd($searchArr);
							foreach($searchArr as $searchValue) {
								$searchValue = trim($searchValue);
								$query->where(function ($q)  use ($searchValue){
									$q->orWhere('firstname', 'like', "%$searchValue%");
									$q->orWhere('lastname', 'like', "%$searchValue%");
								});
							}
							})
                        	  ->orWhereHas('invoiceitem', function($query) use ($search){
                              		$query->where('inp_item_desc', 'like', "%$search%");
                            })
							->orWhere('inv_client_name','like',"%$search%");
            });
		  }
		//   die;
		$allInvoices = $query->orderBy('inv_invoice_date', 'desc')->paginate($length);

		$salestoolsinvoice = SalesToolsInvoice::where('sti_business_id',Session::get('businessId'))->first();
	  	if(count($salestoolsinvoice))
	  		$taxAppliedId = $salestoolsinvoice->sti_override;
	  	else
	  		$taxAppliedId = 0;

	  	$tax_all_data = $this->getTax($taxAppliedId);
	  	$taxdata = $tax_all_data['taxdata']; 
	  	$alltax  = $tax_all_data['alltax'];
	  	$paymenttype = $this->getPaymentType();
	  	$discount = $this->getDiscount();

	  	$loginUserInfo = Auth::user();
		$userInfo[$loginUserInfo->id] = $loginUserInfo->name;
		$countries = ['' => '-- Select --'] + \Country::getCountryLists();

	  	return view('invoices.index',compact('allInvoices', 'totalAmount', 'totalPaid','salestoolsinvoice','discount','alltax','taxdata','userInfo','paymenttype','countries'));
  	}
  	

  	/**
  	 * rander invoice show blade
  	 * @param invoice id
  	 * @return show blade 
  	 */ 
  	public function show($id){
	  	if(!Auth::user()->hasPermission(Auth::user(), 'view-invoices'))
		  	abort(404);

		$paidAmount = $dueAmount= 0;
	  	$invoiceItemsDetails = array();
	  	$invoices = array();
	  	$emailLogs = InvoiceEmailLog::where('iel_invoice_id',$id)->get();

	  	$invoiceDetails = Invoice::with('clientWithTrashed.parqWithTrashed','staffWithTrashed')->find($id);
		$isOffline = $invoiceDetails->inv_client_id == 0?true:false;
	  	$invoiceItemsInfo = InvoiceItems::where('inp_invoice_id',$id)->get();

	  	$serviceBookings = $invoiceItemsInfo->where('inp_type', 'service');
	  	if($serviceBookings->count()){
	  		$serviceBookings = $serviceBookings->pluck('inp_product_id')->toArray();
	  		$serviceBookings = StaffEventSingleService::where('sess_client_attendance', 'Attended')->select('sess_id')->find($serviceBookings);
	  		$serviceBookings = $serviceBookings->pluck('sess_id')->toArray();
	  	}
	  	else
	  		$serviceBookings = [];

	  	$classBookings = $invoiceItemsInfo->where('inp_type', 'class');
	  	if($classBookings->count() && !$isOffline){
	  		$classBookings = $classBookings->pluck('inp_product_id')->toArray();
	  		$classBookings = $invoiceDetails->clientWithTrashed->eventClasses()->whereIn('sec_id', $classBookings)->where('secc_client_attendance', 'Attended')->get();
	  		$classBookings = $classBookings->pluck('sec_id')->toArray();
	  	}
	  	else
	  		$classBookings = [];

	  	$subTotal = 0;
	  	$vatAmount = 0;
	  	$totalAmount = 0;
	  	$staffName = '';
	  	foreach($invoiceItemsInfo as $invoiceItems){
		  	$subTotal += $invoiceItems->inp_total;
		  	$vatAmount = ($invoiceItems->inp_total)*0.1;
		  	$totalAmount = $subTotal + $vatAmount;
		  	$detail = array('type' =>ucfirst($invoiceItems->inp_type),'desc' =>$invoiceItems->inp_item_desc,'unit_price' =>$invoiceItems->inp_price,'quantity'	=>$invoiceItems->inp_quantity,'total' =>$invoiceItems->inp_total,'pd_id' =>$invoiceItems->inp_product_id,'subtotal' => $subTotal,'total_amount' => $totalAmount,'vat_amount' => $vatAmount,'inp_tax'=>$invoiceItems->inp_tax,'inp_tax_type'=>$invoiceItems->inp_tax_type, 'inp_paid_using_epic_credit' => $invoiceItems->inp_paid_using_epic_credit); 
		  	if($invoiceItems->inp_type == 'service' && count($serviceBookings) && in_array($invoiceItems->inp_product_id, $serviceBookings))
		  		$detail['attendance'] = 'Attended';
		  	else if($invoiceItems->inp_type == 'class' && count($classBookings) && in_array($invoiceItems->inp_product_id, $classBookings))
		  		$detail['attendance'] = 'Attended';
		  	else
		  		$detail['attendance'] = '';
		  	$invoiceItemsDetails[] = $detail;
		}
		
		$loginUserInfo = Auth::user();
		$userInfo[$loginUserInfo->id] = $loginUserInfo->name;
		$paymentDetails = Payment::where('pay_invoice_id', $id)->orderBy('pay_id', 'desc')->get();
		$paidAmount = $invoiceDetails->totalPayamount();
		$dueAmount = $invoiceDetails->inv_total - $paidAmount;
		if($dueAmount < 0)
			$dueAmount = 0;

		if($isOffline){
			$clientAddress1 =  $invoiceDetails->address_line_one;
			$clientAddress2 =  $invoiceDetails->address_line_two;
			$clientCity =  $invoiceDetails->city;
			$clientState =  $invoiceDetails->state;
			$clientCountry =  $invoiceDetails->country;
			$clientPostalCode = $invoiceDetails->postal_code;
			$clientEmail = null;
			$clientPhoneNo = null;
			$clientName =  $invoiceDetails->inv_client_name;
		}else{
			$clientDetails = $invoiceDetails->clientWithTrashed->parqWithTrashed;
			$clientAddress1 =  $clientDetails->addressline1;
			$clientAddress2 =  $clientDetails->addressline1;
			$clientCity =  $clientDetails->city;
			$clientState =  $clientDetails->addrState;
			$clientCountry =  $clientDetails->country;
			$clientPostalCode = $clientDetails->postal_code;
			$clientEmail = $clientDetails->email;
			$clientPhoneNo = $clientDetails->contactNo;
			$clientName =  $invoiceDetails->clientWithTrashed->firstname.' '.$invoiceDetails->clientWithTrashed->lastname;
		}
		
		
		$allLocationArea = $invoiceDetails->location();
		if(array_key_exists($invoiceDetails->inv_area_id, $allLocationArea))
		  	$locationName = $allLocationArea[$invoiceDetails->inv_area_id];
		else
		  	$locationName='';

		$invoices = array('invoice_id' => $id,'invoice_no'=>$invoiceDetails->inv_invoice_no,'location_name'=>$locationName,'invoice_date' =>$invoiceDetails->inv_invoice_date,'due_date' =>$invoiceDetails->inv_due_date, 'client_name' =>$clientName,'addr1' => $clientAddress1,'addr2' => $clientAddress2,'city' => $clientCity,'state' => $clientState,'country' => $clientCountry,'postalCode'=>$clientPostalCode,'email'=>$clientEmail,'phone_no'=>$clientPhoneNo,'client_id'=>$invoiceDetails->inv_client_id,'note' =>$invoiceDetails->inv_notes,'ref' =>$invoiceDetails->inv_ref,'status'=>$invoiceDetails->inv_appointment_status,'total' =>$invoiceDetails->inv_total,'payment_status' =>$invoiceDetails->inv_status,'paid_amount' => $paidAmount,'due_amount' => $dueAmount,'tax-amount' =>$invoiceDetails->inv_incl_tax,'loc_id'=>$invoiceDetails->inv_area_id,'staffId' => $invoiceDetails->staffId,'staffName' =>$invoiceDetails->staffWithTrashed->first_name." ".$invoiceDetails->staffWithTrashed->last_name,'termsOfSale' => $invoiceDetails->termsOfSale,'delivery_type' => $invoiceDetails->delivery_type,'contact_person' => $invoiceDetails->contact_person,'address_line_one' => $invoiceDetails->address_line_one,'address_line_two' => $invoiceDetails->address_line_two,'del_city'=>$invoiceDetails->city,'del_state' => $invoiceDetails->state,'del_country' => $invoiceDetails->country,'del_postal_code'=>$invoiceDetails->postal_code,'shipped_via'=>$invoiceDetails->shipped_via,'order_number'=>$invoiceDetails->order_number,'cust_order_number'=>$invoiceDetails->cust_order_number); 

		$salestoolsinvoice = SalesToolsInvoice::where('sti_business_id',Session::get('businessId'))->first();
	  	if(count($salestoolsinvoice))
	  		$taxAppliedId = $salestoolsinvoice->sti_override;
	  	else
	  		$taxAppliedId = 0;

	  	$tax_all_data = $this->getTax($taxAppliedId);
	  	$taxdata = $tax_all_data['taxdata']; 
	  	$alltax  = $tax_all_data['alltax'];
	  	$paymenttype = $this->getPaymentType();
		$discount = $this->getDiscount();
		$businessData  = Business::whereId(session()->get('businessId'))->first()->toArray();
		$countries = ['' => '-- Select --'] + \Country::getCountryLists();
	 	return view('invoices.show', compact('emailLogs','invoices','invoiceItemsDetails','paymentDetails','userInfo','allLocationArea','salestoolsinvoice','paymenttype','taxdata','discount','alltax','businessData','countries'));
  	}

  
  	/**
  	 * Store invoice data
  	 * @param Array Form data
  	 * @return Status response
  	 */ 
	public function store(Request $request){
		$response = array('status' => '','data' =>'');
		$formData = $request->all();
  		$salestoolsinvoice = SalesToolsInvoice::where('sti_business_id',Session::get('businessId'))->first();
    	$invoiceObj = new Invoice;
    	$invoiceObj->inv_invoice_date = $formData['invoiceDate'];
    	$invoiceObj->inv_due_date = $formData['dueDate'];
    	$invoiceObj->inv_business_id = Session::get('businessId'); 
    	$invoiceObj->inv_client_id = isset($formData['clientId'])?$formData['clientId']:0;
		$invoiceObj->inv_client_name = $formData['clientName'];
    	$invoiceObj->inv_notes = $formData['notes'];
    	$invoiceObj->inv_ref = $formData['reference'];
	    $invoiceObj->inv_area_id = $formData['location-area'];
	    $invoiceObj->inv_total = $formData['totalAmount'];
	    $invoiceObj->inv_incl_tax = $formData['totalTaxAmount'];
	    $invoiceObj->inv_status = 'Unpaid';
	    $invoiceObj->staffId = $formData['staffId'];
	    $invoiceObj->termsOfSale = $formData['termsOfSale'];
	    $invoiceObj->delivery_type = $formData['delivery_type'];
	    $invoiceObj->contact_person = $formData['contact_person'];
	    $invoiceObj->address_line_one = $formData['address_line_one'];
	    $invoiceObj->address_line_two = $formData['address_line_two'];
	    $invoiceObj->city = $formData['city'];
	    $invoiceObj->country = $formData['country'];
	    $invoiceObj->state = $formData['state'];
	    $invoiceObj->postal_code = $formData['postal_code'];
	    $invoiceObj->shipped_via = $formData['shipped_via'];
	    $invoiceObj->order_number = $formData['order_number'];
	    $invoiceObj->cust_order_number = $formData['cust_order_number'];
	    if(isset($formData['appointment-status'])){
			$invoiceObj->inv_appointment_status = $formData['appointment-status'];
			$markAppointmentCheck = true;
	    } else {
		  	$invoiceObj->inv_appointment_status = '';
		  	$markAppointmentCheck = false;
	    }
    	$invoiceNo = $salestoolsinvoice->sti_next_invoice_number;
    	$invoiceObj->inv_invoice_no = $invoiceNo;
    	if($invoiceObj->save()){
    		$invoiceNo++;
    		$salestoolsinvoice->sti_next_invoice_number = $invoiceNo;
    		$salestoolsinvoice->save();	
	  		$lastInsertId = $invoiceObj->inv_id;
	  		$invoiceItems = Invoice::invoiceItems($formData, $lastInsertId, $markAppointmentCheck);
	  		if($invoiceItems){
	  			if($formData['paymAmount'] != '' && $formData['paymAmount'] != 0){
	  				$paymentInfo['paymAmount'] = $formData['paymAmount'];
	  				$paymentInfo['paymentMethod'] = $formData['payment_type'];
	  				$paymentInfo['paymProcess'] = $formData['paymProcess'];
	  				$paymentInfo['paymentDate'] = $formData['paymentDate'];
	  				$this->setPayment($invoiceObj, $paymentInfo);
	  			}
		  		$response['status'] = 'success';
		  		$response['data'] =	$lastInsertId;
	  		}
    	}
        return json_encode($response);
    }
	

	/**
	 * Update invoice and invoice item
	 * @param Array Form data
	 * @return Status response 
	 */ 
	public function update(Request $request){
		$response = array('status' => '','data' =>'');
		$invoiceInfo = $request->all();

		$invoiceInfoById = Invoice::find($invoiceInfo['invoiceId']);

		/* Start: Update Payment */
  		$paymentInfo = Payment::where('pay_invoice_id', $invoiceInfo['invoiceId'])->orderBy('pay_id', 'DESC')->first();
 		$epicAmount=0;
 		$payStatus='';
	  	if(count($paymentInfo)){
	  		if($paymentInfo->pay_total_invoice_amount < $invoiceInfo['totalAmount']){
		  		$due_amount = $paymentInfo->pay_outstanding_amount;
			  	if($due_amount > 0){
			  		$final_amount = $invoiceInfo['totalAmount'] + $due_amount;
			  	}
			  	else{
			  		$final_amount = $invoiceInfo['totalAmount'];
			  	}
			}  	
		  	else{
		  		$final_amount = $invoiceInfo['totalAmount'];
		  	}

		  	$paymentInfo->pay_total_invoice_amount = $invoiceInfo['totalAmount'];
		  	$payAmount = $invoiceInfoById->totalPayamount();
		  	$epicAmount = $payAmount - $final_amount;
		  	if($epicAmount > 0){
		  		$outstandingAmount = 0;
		  		$payStatus = 'Paid';
		  		//set EPIC balance
		  		//$this->epicAmountSet($invoiceInfo['clientId'], $epicAmount);
					//$paymentInfo->pay_amount = $final_amount;
		  		$this->updateEpicCredit($invoiceInfo['clientId'], $epicAmount, 'invoice_amount');
		  	} else{
		  		$outstandingAmount = abs($epicAmount);
		  		$payStatus = 'Unpaid';
		  	}
		  	$paymentInfo->pay_outstanding_amount = $outstandingAmount;
		  	$paymentInfo->update();
	  	}
	  	else{
	  		$final_amount = $invoiceInfo['totalAmount'];
	  		$payStatus = 'Unpaid';
	  	}
		/* End: Edit Payment */

		if($invoiceInfo['location-area']){
			$locationId = $invoiceInfo['location-area'];
		}else{
			$locationId = 0;
		}
	  	$invoiceItemsById = InvoiceItems::where('inp_invoice_id', $invoiceInfo['invoiceId'])->get();
	  	$productStock = array();
	  	if(count($invoiceItemsById)){
	  		foreach ($invoiceItemsById as $value) {
	  			if($value->inp_type == 'product')
	  					$productStock[$value->inp_product_id] = $value->inp_quantity;
	  		}
	  	}

	  	$invoiceInfoById->inv_invoice_date = $invoiceInfo['invoiceDate'];
	  	$invoiceInfoById->inv_due_date = $invoiceInfo['dueDate'];
	  	$invoiceInfoById->inv_client_id = $invoiceInfo['clientId'];
	  	$invoiceInfoById->inv_incl_tax = $invoiceInfo['totalTaxAmount'];
	  	$invoiceInfoById->inv_notes = $invoiceInfo['notes'];
		$invoiceInfoById->inv_ref = $invoiceInfo['reference'];
		$invoiceInfoById->staffId = $invoiceInfo['staffId'];
	    $invoiceInfoById->termsOfSale = $invoiceInfo['termsOfSale'];
	    $invoiceInfoById->delivery_type = $invoiceInfo['delivery_type'];
	    $invoiceInfoById->contact_person = $invoiceInfo['contact_person'];
	    $invoiceInfoById->address_line_one = $invoiceInfo['address_line_one'];
	    $invoiceInfoById->address_line_two = $invoiceInfo['address_line_two'];
	    $invoiceInfoById->city = $invoiceInfo['city'];
	    $invoiceInfoById->country = $invoiceInfo['country'];
	    $invoiceInfoById->state = $invoiceInfo['state'];
	    $invoiceInfoById->postal_code = $invoiceInfo['postal_code'];
	    $invoiceInfoById->shipped_via = $invoiceInfo['shipped_via'];
	    $invoiceInfoById->order_number = $invoiceInfo['order_number'];
	    $invoiceInfoById->cust_order_number = $invoiceInfo['cust_order_number'];
	  	$invoiceInfoById->inv_area_id = $locationId;
	  	$invoiceInfoById->inv_status = $payStatus;
	  	$invoiceInfoById->inv_total = $final_amount;
	  	if(array_key_exists('appointment-status',$invoiceInfo)){
		  	$invoiceInfoById->inv_appointment_status = $invoiceInfo['appointment-status'];
		  	$markAppointmentCheck = true;
	  	} 
	  	else {
		  	$invoiceInfoById->inv_appointment_status = '';
		  	$markAppointmentCheck = false;
	  	}

		if($invoiceInfoById->update()){
			Invoice::deleteInvoiceItem($invoiceInfo['invoiceId'], true);
	  		$invoiceItems = Invoice::invoiceItems($invoiceInfo, $invoiceInfo['invoiceId'], $markAppointmentCheck, $productStock);
	  		if($invoiceItems){
	  			if($invoiceInfo['paymAmount'] != '' && $invoiceInfo['paymAmount'] != 0){
	  				$paymentInfo['paymAmount'] = $invoiceInfo['paymAmount'];
	  				$paymentInfo['paymentMethod'] = $invoiceInfo['payment_type'];
	  				$paymentInfo['paymProcess'] = $invoiceInfo['paymProcess'];
	  				$paymentInfo['paymentDate'] = $invoiceInfo['paymentDate'];
	  				$this->setPayment($invoiceInfoById, $paymentInfo);
	  			}
		  		$response['status'] = 'success';
		  		$response['data'] =	$invoiceInfo['invoiceId'];
	  		}
		}
		return json_encode($response);
	}
  	
    
  	/**
  	 * Send invoice mail to user
  	 * @param Array form data
  	 * @return Array response 
  	 */
  	public function sendInvoiceMail(Request $request){
	  	$response = array('status' => '','data' =>''); 
	 	$invoice = array();
	  	$msg = $request->msg;
		$subj ="Epic Trainer: Invoice";
		$invoiceData = $this->getAllInvoiceData($request->invoice_id);
		$file = generateInvoicePdf($invoiceData);
	  	$currentDateTime = date('Y/m/d h:i:sa');
	  	$mailData = [
	  			'subject' => $subj,
	  			'name' => $request->client_name,
				'toEmail' => $request->email_id,
				'filePath' => public_path('pdf-invoices/'.$file),
	  		];
  		$templateData = [
			  'name' => $request->client_name,
			  'message' => $request->msg
  		];
	  	$html = view('invoices.verification_email',compact('templateData'))->render();
	  	$response = sendMail($mailData,$html);
	  	$response['status'] = $response['status'];
	  	$response['data'] = $response['message'];
	  	if($response['status'] == 'success'){
	  		$emailLogs = new InvoiceEmailLog;
		  	$emailLogs->iel_invoice_id = $request->invoice_id;
		  	$emailLogs->iel_outstanding_amount = $request->os_amount;
		  	$emailLogs->iel_due_amount =$request->due_amount;
		  	$emailLogs->iel_date_time = $currentDateTime;
		  	$emailLogs->iel_to_mail = $request->email_id;
		  	$emailLogs->save();
	  	}
	  	return json_encode($response); 	
  	}
  	

  	/**
  	 * Get loaction and area for invoice
  	 * @param void
  	 * @return Array area and location 
  	 */
  	public function getAreas(){
  		$allLocationArea = Invoice::location();
  		return json_encode($allLocationArea);
  	}

  	
	/**
  	 * Get all staff of given area
  	 * @param void
  	 * @return Array staff 
  	 */
  	public function getStaff(Request $request){	  	
	  	$staffDeatils = array();
	  	$response = array('status' => '','staffs' =>''); 		  	
	  	$location = Location::withTrashed()->with('areasWithTrashed')->find($request->area_id);
 	  	if($location){ 
 	  		$staffDeatils=[];
 	  		$staffs = [];
 	  		if($location->areasWithTrashed->count()){
	 	  		foreach ($location->areasWithTrashed as  $value) {
	 	  			$staffs[] = $value->staffs;
	 	  		}
	 	  	}

	 	  	if(count($staffs)){
            	foreach($staffs as $staff){
            		if(count($staff)){
	            		foreach ($staff as $value) {
	            			$id=$value->id;
	            			if(!array_key_exists($id, $staffDeatils))
	                			$staffDeatils[$id] = ucwords($value->first_name. ' '.$value->last_name);
	            		}
	            	}	
            	}
            }
	  	}
	  	asort($staffDeatils);
	  	if(count($staffDeatils) > 0){
		  	$response['status'] = 'success';
		  	$response['staffs'] = $staffDeatils;
	  	}
	  	return json_encode($response); 
	}


	/**
  	 * Get all product of given area
  	 * @param void
  	 * @return Array product 
  	 */
  	public function getProduct(Request $request){
  		$productInfo = array();
  		$response = array('status' => '','staffs' =>''); 		  	
	  	$location = Location::withTrashed()->find($request->area_id);
	  	if(count($location)){
			$products = $location->productsWithTrashed;
		  	if($products){
			  	$j = 0;
			  	foreach($products as $product) {
				  	$productInfo[$j]['id'] = $product->id;
				  	$productInfo[$j]['name'] = $product->name;
				  	$productInfo[$j]['sale_price'] = $product->sale_price;
				  	$productInfo[$j]['tax'] = $product->salesTax;
					$productInfo[$j]['stock'] = $product->stock_level;
					if(isset($product->deleted_at)){
						$productInfo[$j]['is_deleted'] = true;
					}else{
						$productInfo[$j]['is_deleted'] = false;
					}
				  	$j++;
			  	}
		  	}
		}
  		if(count($productInfo) >0 ){
  			$response['status'] = 'success';
			$response['products'] = $productInfo;
		}
		return json_encode($response); 	
  	}

  	/**
  	 * Get invoice payment details
  	 * @param invoice id
  	 * @return invoice payment data
  	 */
  	public function getPaymentDetail(Request $request){
  		$response = array('status'=>'error');
  		$invoice = Invoice::find($request->invoiceId);
  		if(count($invoice)){
	  		$paidAmount = $invoice->totalPayamount();
			$dueAmount = round(($invoice->inv_total - $paidAmount), 2);
			if($dueAmount < 0)
				$dueAmount = 0;

	  		$response['due_amount'] = $dueAmount;
	  		$response['total'] = $invoice->inv_total;
	  		if($invoice->inv_status == 'Paid'){
	  			$response['invoiceAmount'] = 0;
	  		}
	  		else{
		  		if($dueAmount > 0 )
		  			$response['invoiceAmount'] = $dueAmount;
		  		else
		  			$response['invoiceAmount'] = $invoice->inv_total;
	  		}

	  		$response['status'] = 'success';
	  	}

  		return json_encode($response);
  	}

  	/**
  	 * Get epic cash
  	 *
  	 * @param
  	 * @return
  	 */
  	public function getEpicCash(Request $request){
  		$epicAmount = \App\Clients::where('id', (int)$request->clientId)->pluck('epic_credit_balance')->first();	
  		if($epicAmount)
  			return $epicAmount;
  		return 0;
  	}


	/**
	 * Invoice delete from index
	 * @param invoice id
	 * @return response
	**/
	public function destroy($id, Request $request){
		$invoice = Invoice::find($id);
        if($invoice){
			$invoice->delete();
        }
		if($request->has('clientId')){
			$root = route('clients.show',$request->clientId).'#invoices';
			return redirect($root);
		}
		elseif($request->has('from') && $request->from == 'view'){
			$root = route('invoices.view');
        	return redirect($root)->with('message', 'success|Invoice has been deleted successfully.');
		}
		else{
        	return redirect()->back()->with('message', 'success|Invoice has been deleted successfully.');
        }	
	}


    /**
	 * get tax data
	 * @param taxAppliedId
	 * @return Array tax data
	**/
 	/*protected function getTax($taxAppliedId){
    	$data = MemberShipTax::where('mtax_business_id',Session::get('businessId'))->select('id','mtax_label','mtax_rate')->get();

    	$response = array('taxdata'=>array(),'alltax'=>array());
    	if($data->count()){
    		if($taxAppliedId)
    			$response['taxdata'] = $data->where('id', $taxAppliedId)->first();
    		else 
    			$response['taxdata'] = $data->first();

    		$response['alltax'] = $data->toArray();
    	}
    	return $response;
	}*/
    

	/**
	 * get Discount 
	 * @param void
	 * @return array discount
	**/
	/*protected function getDiscount(){
		$discount = [];
    	$data = SalesToolsDiscount::with('tax')->where('std_business_id',Session::get('businessId'))->get();
	  	if($data->count()){
	  		$i=0;
		  	foreach ($data as $value) {
		  		$discount[$i]['id']= $value->std_id;
		  		$discount[$i]['name']= $value->std_name;
		  		$discount[$i]['type']= $value->std_type;
		  		$discount[$i]['price']= $value->std_value;
		  		if($value->tax){
			  		$discount[$i]['disTax']= $value->tax->mtax_label;
			  		$discount[$i]['disTaxRate']= $value->tax->mtax_rate;
			  	}else{
			  		$discount[$i]['disTax'] = 'N/A';
			  		$discount[$i]['disTaxRate'] = 0;	
			  	}
		  		$i++;
		  	}
		} 	
    	return $discount;
	}*/
    

	/**
	 * Set payment for invoice
	 *
	 * @param Array $paymentInfo['invoiceId','paymentMethod','paymAmount','invoiceAmount','paymProcess','paymentDate']
	 * @return Array $response[]
	 */
	protected function setPayment($invoiceObj, $paymentInfo){
		$paymentStatus = 'Unpaid';
		$isError = false;

		$paidAmount = $invoiceObj->totalPayamount();
		$dueAmount = round($invoiceObj->inv_total - $paidAmount);
		if($dueAmount < 0)
			$paymentInfo['invoiceAmount'] = $invoice->inv_total;
  		else
  			$paymentInfo['invoiceAmount'] = $dueAmount;
  		
	  	$clientObj = $invoiceObj->client;
	  	$paymentMethod = $paymentInfo['paymentMethod'];
	  	if($paymentMethod == 'EPIC Credit'){
	  		if($clientObj->epic_credit_balance >= $paymentInfo['paymAmount']){
	  			$remainingAmount = $paymentInfo['invoiceAmount'] - $paymentInfo['paymAmount'];
	  			if($remainingAmount <= 0){
			 		$remainingAmount = 0;  
				}
	  			$paymentStatus = 'Paid';
	  			$payAmount = $paymentInfo['paymAmount'];
	  			$userId = $paymentInfo['paymProcess'];
	  			$refer = '';
				$paydate = date('Y-m-d',strtotime($paymentInfo['paymentDate']));
	  			$this->updateEpicCredit($clientObj->id, $payAmount, 'invoice_amount');
		  	}
	  		else{
	  			$isError = true;
	  		}
	  	}
	  	else{
		  	$remainingAmount = $paymentInfo['invoiceAmount'] - $paymentInfo['paymAmount'];
		  	if($remainingAmount <= 0){
			 	$remainingAmount = 0;  
			}
			$payAmount = $paymentInfo['paymAmount'];
			$userId = $paymentInfo['paymProcess'];
			$refer = '';
			$paydate = date('Y-m-d',strtotime($paymentInfo['paymentDate']));
		}
		if(!$isError){
			if($remainingAmount == 0){
				$paymentStatus = 'Paid'; 
			}
			else{
				$paymentStatus = 'Unpaid';
			}
		  	$paymentObj = new Payment;
		  	$paymentObj->pay_invoice_id = $invoiceObj->inv_id;
		  	$paymentObj->pay_total_invoice_amount = $invoiceObj->inv_total;
		  	$paymentObj->pay_amount = $payAmount;
		  	$paymentObj->pay_outstanding_amount = $remainingAmount;
		  	$paymentObj->pay_confirm_date = $paydate;
		  	$paymentObj->pay_processed_by = $userId;
		  	$paymentObj->pay_ref = $refer;
		  	$paymentObj->pay_type = $paymentMethod;
		  	if($paymentObj->save()){
				$invoiceObj->inv_status = $paymentStatus;
				$invoiceObj->save();
				return true;
		 	}
		}
		return false;	
	}



	/** ------------------------------------------------------------------- **/
	/* Client Invoice
	/** ------------------------------------------------------------------- **/

	/**
	 * list all invoices
	 * @param void
	 * @return list
	**/
  	public function resultIndex(Request $request){
		$selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        if(isset($selectedMenus) && !in_array('invoice', explode(',', $selectedMenus))){
            return redirect('access-restricted');
        }
  		$clientSelectedMenus = [];
        if(Auth::user()->account_type == 'Client') {
            $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
 
            if(!in_array('invoice', $clientSelectedMenus))
              return redirect('access-restricted');
        }  

		$businessId = Session::get('businessId');
		$prod_arr = '';
  	  	if(!empty($_GET['p']) && !empty($_GET['q']))
  	  		$prod_arr = json_encode(array_combine(explode(',', $_GET['p']), explode(',', $_GET['q'])));	

  	  	$openModal =  $request->id;	
		$search = $request->get('search');

		if(isset($_COOKIE['invoice_length']))
			$length = (int)$_COOKIE['invoice_length'];
		else
			$length = 10;

		$totalAmount = 0;
		$totalPaid = 0;
	  	$totalAmount = Invoice::where('inv_business_id',$businessId)->where('inv_client_id',Auth::user()->account_id)->sum('inv_total');
	  	$totalPaid = Invoice::where('inv_business_id',$businessId)->where('inv_client_id',Auth::user()->account_id)->where('inv_status', 'Paid')->sum('inv_total');

	  	$cookieName = 'invoice-list-status-filter';
	  	if(isset($_COOKIE[$cookieName]))
        	$status = $_COOKIE[$cookieName];
        else
        	$status = '';
        
        if(isset($_COOKIE['dueEndDate']) && isset($_COOKIE['dueSatrtDate'])){
        	$startDueDate = $_COOKIE['dueSatrtDate'];
        	$endDueDate = $_COOKIE['dueEndDate'];
        }
        else{
        	$startDueDate = 'null';
        	$endDueDate = 'null';
        }

	  	$query = Invoice::where('inv_business_id',Session::get('businessId'))->where('inv_client_id', Auth::user()->account_id);
	  	if($startDueDate != 'null' && $endDueDate != 'null')
	  		$query->where('inv_due_date','>=', $startDueDate)->where('inv_due_date','<=',$endDueDate);
	  	if($status)
	  		$query->where('inv_status', $status);
	  	if($search){
	  		$query->where(function($query) use($search){
                        $query->whereHas('client', function($query) use ($search){
                              		$query->orWhere('firstname', 'like', "%$search%")
                              			  ->orWhere('lastname', 'like', "%$search%");
                              })
                        	  ->orWhereHas('invoiceitem', function($query) use ($search){
                              		$query->where('inp_item_desc', 'like', "%$search%");
                              })
                              ->orWhereHas('loc', function($query) use ($search){
                              		$query->where('location_training_area', 'like', "%$search%");
                              });
                 	});
	  	}
		$allInvoices = $query->orderBy('inv_invoice_date', 'DESC')->paginate($length);

	  	return view('Result.invoices.index',compact('allInvoices','openModal','prod_arr', 'totalAmount', 'totalPaid'));
  	}

  	/***** Download Invoice *******/

  	/**
  	 * @param int $id
  	 * @return string
  	**/
  	public function downloadInvoice($id){
		$invoiceData = $this->getAllInvoiceData($id);
		$file = generateInvoicePdf($invoiceData);
		$filePath = public_path('pdf-invoices/'.$file);
		return Response::download($filePath);
  	}


  public function getAllInvoiceData($id){
	  	$invoiceItemsDetails = array();
	  	$invoices = array();

	  	$invoiceDetails = Invoice::with('clientWithTrashed.parqWithTrashed','staffWithTrashed')->find($id);

	  	$invoiceItemsInfo = InvoiceItems::where('inp_invoice_id',$id)->get();

	  	$subTotal = 0;
	  	$vatAmount = 0;
	  	$totalAmount = 0;
	  	$staffName = '';
	  	foreach($invoiceItemsInfo as $invoiceItems){
		  	if($invoiceItems->inp_staff_id > 0){
			  	$staff = Staff::withTrashed()->find($invoiceItems->inp_staff_id);
			  	if(count($staff))
			  		$staffName = $staff->first_name.' '.$staff->last_name;
		  	}
		  	$subTotal += $invoiceItems->inp_total;
		  	$vatAmount = ($invoiceItems->inp_total)*0.1;
		  	$totalAmount = $subTotal + $vatAmount;
		  	$detail = array('staff_name' => $staffName,'type' =>ucfirst($invoiceItems->inp_type),'desc' =>$invoiceItems->inp_item_desc,'unit_price' =>$invoiceItems->inp_price,'quantity'	=>$invoiceItems->inp_quantity,'total' =>$invoiceItems->inp_total,'pd_id' =>$invoiceItems->inp_product_id,'subtotal' => $subTotal,'total_amount' => $totalAmount,'vat_amount' => $vatAmount,'staff_id' => $invoiceItems->inp_staff_id,'inp_tax'=>$invoiceItems->inp_tax,'inp_tax_type'=>$invoiceItems->inp_tax_type, 'inp_paid_using_epic_credit' => $invoiceItems->inp_paid_using_epic_credit); 
		  	$invoiceItemsDetails[] = $detail;
		}
		
		$loginUserInfo = Auth::user();
		$userInfo[$loginUserInfo->id] = $loginUserInfo->name;
		$paymentDetails = Payment::where('pay_invoice_id', $id)->orderBy('pay_id', 'desc')->get();
		$paidAmount = $invoiceDetails->totalPayamount();
		$dueAmount = $invoiceDetails->inv_total - $paidAmount;
		if($dueAmount < 0)
			$dueAmount = 0;
		$isOffline = $invoiceDetails->inv_client_id == 0?true:false;
		if($isOffline){
			$clientAddress1 =  $invoiceDetails->address_line_one;
			$clientAddress2 =  $invoiceDetails->address_line_two;
			$clientCity =  $invoiceDetails->city;
			$clientState =  $invoiceDetails->state;
			$clientCountry =  $invoiceDetails->country;
			$clientPostalCode = $invoiceDetails->postal_code;
			$clientEmail = null;
			$clientPhoneNo = null;
			$clientName =  $invoiceDetails->inv_client_name;
		}else{
			$clientDetails = $invoiceDetails->clientWithTrashed->parqWithTrashed;
			$clientAddress1 =  $clientDetails->addressline1;
			$clientAddress2 =  $clientDetails->addressline1;
			$clientCity =  $clientDetails->city;
			$clientState =  $clientDetails->addrState;
			$clientCountry =  $clientDetails->country;
			$clientPostalCode = $clientDetails->postal_code;
			$clientEmail = $clientDetails->email;
			$clientPhoneNo = $clientDetails->contactNo;
			$clientName =  $invoiceDetails->clientWithTrashed->firstname.' '.$invoiceDetails->clientWithTrashed->lastname;
		}
		
		
		$allLocationArea = $invoiceDetails->location();
		if(array_key_exists($invoiceDetails->inv_area_id, $allLocationArea))
		  	$locationName = $allLocationArea[$invoiceDetails->inv_area_id];
		else
		  	$locationName='';

		$invoices = array('invoice_id' => $id,'invoice_no'=>$invoiceDetails->inv_invoice_no,'location_name'=>$locationName,'invoice_date' =>$invoiceDetails->inv_invoice_date,'due_date' =>$invoiceDetails->inv_due_date, 'client_name' =>$clientName,'addr1' => $clientAddress1,'addr2' => $clientAddress2,'city' => $clientCity,'state' => $clientState,'country' => $clientCountry,'postalCode'=>$clientPostalCode,'email'=>$clientEmail,'phone_no'=>$clientPhoneNo,'client_id'=>$invoiceDetails->inv_client_id,'note' =>$invoiceDetails->inv_notes,'ref' =>$invoiceDetails->inv_ref,'status'=>$invoiceDetails->inv_appointment_status,'total' =>$invoiceDetails->inv_total,'payment_status' =>$invoiceDetails->inv_status,'paid_amount' => $paidAmount,'due_amount' => $dueAmount,'tax-amount' =>$invoiceDetails->inv_incl_tax,'loc_id'=>$invoiceDetails->inv_area_id,'staffId' => $invoiceDetails->staffId,'staffName' =>$invoiceDetails->staffWithTrashed->first_name." ".$invoiceDetails->staffWithTrashed->last_name,'termsOfSale' => $invoiceDetails->termsOfSale,'delivery_type' => $invoiceDetails->delivery_type,'contact_person' => $invoiceDetails->contact_person,'address_line_one' => $invoiceDetails->address_line_one,'address_line_two' => $invoiceDetails->address_line_two,'del_city'=>$invoiceDetails->city,'del_state' => $invoiceDetails->state,'del_country' => $invoiceDetails->country,'del_postal_code'=>$invoiceDetails->postal_code,'shipped_via'=>$invoiceDetails->shipped_via,'order_number'=>$invoiceDetails->order_number,'cust_order_number'=>$invoiceDetails->cust_order_number); 

		$salestoolsinvoice = SalesToolsInvoice::where('sti_business_id',Session::get('businessId'))->first()->toArray();
	  	if(count($salestoolsinvoice))
	  		$taxAppliedId = $salestoolsinvoice->sti_override;
	  	else
	  		$taxAppliedId = 0;

	  	$tax_all_data = $this->getTax($taxAppliedId);
	  	$taxdata = $tax_all_data['taxdata'];
	  	$bussinessData  = Business::whereId(session()->get('businessId'))->first()->toArray();
	  	$invoiceData = [
	  		'invoice' => $invoices,
			'invoice_items' => $invoiceItemsDetails,
			'sales_tools_invoice' => $salestoolsinvoice,
			'tax_data' => $taxdata,
			'business_data' => $bussinessData,
	  	];
	  	return $invoiceData;
  }
}