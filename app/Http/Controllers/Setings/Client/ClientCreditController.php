<?php
namespace App\Http\Controllers\Setings\Client;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use App\ClientCredit;

class ClientCreditController extends Controller{
    public function store(Request $request){
        $msg = [];

        if(isUserType(['Admin'])){
            $clientCredit = new ClientCredit;
            $clientCredit->cc_user_id = Auth::user()->id;
            $clientCredit->cc_client_id = $request->clientId;
            $clientCredit->cc_amount = $request->creditAmount;
            $clientCredit->cc_reason = $request->creditReason;
            if($request->creditExpireNever == 1)
                $clientCredit->cc_expiry = '';
            else
                $clientCredit->cc_expiry = $request->creditExpire;

            $clientCredit->save();

            $msg['status'] = 'added';
            $msg['message'] = displayAlert('success|Credits have been issued successfully.');
        }

        return json_encode($msg);
    }
}   