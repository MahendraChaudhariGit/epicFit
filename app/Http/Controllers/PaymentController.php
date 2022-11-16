<?php
namespace App\Http\Controllers;
use Auth;
use App;
use PDF;
use View;
use Mail;
use DB;
use App\InvoiceEmailLog;
use Illuminate\Http\Request;
use App\Http\Traits\HelperTrait;
use Session;
use App\Business;
use App\Payment;
use App\Invoice;
use App\Makeup;
use App\Http\Requests;
class PaymentController extends Controller{
	use HelperTrait;

	/**
	 * Invoice payment
	 * @param formdata
	 * @return response
	 */
	public function store(Request $request){
	  	$response = array('status' => '','data' =>'','remaining_amount' =>'');
	  	$paymentInfo = $request->all();

		$invoiceObj = Invoice::find($paymentInfo['invoiceId']);
	  	$clientObj = $invoiceObj->client;
		$paymentMethod = trim($paymentInfo['paymentMethod']);
		if($paymentInfo['invoiceAmount'] >= $paymentInfo['paymAmount']){
			if($paymentMethod == 'EPIC Credit'){
				if($clientObj->epic_credit_balance >= $paymentInfo['paymAmount']){
					$remainingAmount = $paymentInfo['invoiceAmount'] - $paymentInfo['paymAmount'];
					if($remainingAmount <= 0){
					   $remainingAmount = 0;  
				  }
					$paymentStatus = 'Paid';
					$payAmount = $paymentInfo['paymAmount'];
					$userId = $paymentInfo['paymProcess'];
					$refer = $paymentInfo['paymReference'];
				  $paydate = date('Y-m-d',strtotime($paymentInfo['paymDate']));
					$this->updateEpicCredit($clientObj->id, $payAmount, 'invoice_amount');
				}
				else{
					$response['status'] ='overflow';
					$response['amount'] = $paymentInfo['paymAmount']-$clientObj->epic_credit_balance;
					return json_encode($response);
				}
			}
			/*elseif($paymentMethod == 'Direct Debit' || $paymentMethod == ''){
				$paymentMethod = 'Direct Debit';
				$remainingAmount = 0;
				$payAmount = $paymentInfo['paymAmount'];
				$userId = Auth::id();
				$refer = '';
				$paydate = date('Y-m-d');
			}*/
			else{
				$remainingAmount = $paymentInfo['invoiceAmount'] - $paymentInfo['paymAmount'];
				if($remainingAmount <= 0){
				   $remainingAmount = 0;  
			  }
			  $payAmount = $paymentInfo['paymAmount'];
			  $userId = $paymentInfo['paymProcess'];
			  $refer = $paymentInfo['paymReference'];
			  $paydate = date('Y-m-d',strtotime($paymentInfo['paymDate']));
		  }
		  if($remainingAmount == 0){
			  $paymentStatus = 'Paid'; 
		  }
		  else{
			  $paymentStatus = 'Unpaid';
		  }
			$paymentObj = new Payment;
			$paymentObj->pay_invoice_id = $paymentInfo['invoiceId'];
			$paymentObj->pay_total_invoice_amount = $paymentInfo['totalinvoiceAmount'];
			$paymentObj->pay_amount = $payAmount;
			$paymentObj->pay_outstanding_amount = $remainingAmount;
			$paymentObj->pay_confirm_date = $paydate;
			$paymentObj->pay_processed_by = $userId;
			$paymentObj->pay_ref = $refer;
			$paymentObj->pay_type = $paymentMethod;
			if($paymentObj->save()){
			  $lastPaymentId = $paymentObj->pay_id;
			  $invoiceObj->inv_status = $paymentStatus;
			  $invoiceObj->save();
			  $response['status'] = 'success';
			  $response['data'] =	$lastPaymentId;
			  $response['remaining_amount'] =	$lastPaymentId;
		   }
		}else{
			$response['status'] ='overDue';
			$response['message'] = "Due Amount is less than Entered Amount";
		}
	  	return json_encode($response);
  	}


  	/**
	 * Payment Delete
	 * @param formdata
	 * @return response
	 */
	public function delete(Request $request){
		$response = array('status' => '','msg' =>'');
		$deletePayment = Payment::find($request->id);
		if($deletePayment){
			$deletePayment->delete();
			$response['status'] =  "success";
			$response['msg'] = "payment delete successfully";
		 }
		return json_encode($response);    	
	}
}