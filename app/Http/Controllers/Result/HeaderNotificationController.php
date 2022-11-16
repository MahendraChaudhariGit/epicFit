<?php

namespace App\Http\Controllers\Result;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Session;
use Auth;
use DB;
use App\Result\User;
use App\SocialUserDirectMessage;
use App\Clients;
use View;
use Response;

class HeaderNotificationController extends Controller {

    public function index(){
            $user = Auth::user();
            
            $response['status'] = 'success';
            $response['count'] = SocialUserDirectMessage::where('seen', 0)
                                ->where('receiver_delete', 0)
                                ->where('receiver_user_id', $user->account_id)
                                ->where('sender_user_id', '!=', null)
                                ->where('seen', '0')
                                ->count();

            $message = SocialUserDirectMessage::with(['receiver','sender'])
                ->where('receiver_delete', 0)
                ->where('receiver_user_id', $user->account_id)
                ->where('sender_user_id', '!=', null)
                ->where('seen', '0')
                ->get();

            $senders = [];
            $data = [];

            foreach ($message as $key => $value) {
               $senders[] = $value->sender_user_id;
            }

            $senders = array_values(array_unique($senders));

            foreach ($senders as $key1 => $value) {
                foreach ($message as $key => $msg) {
                    if($value == $msg->sender_user_id){
                        $data[$key1]['id'] = $msg->id;
                        $data[$key1]['sender_user_id'] = $msg->sender_user_id;
                        $data[$key1]['receiver_user_id'] = $msg->receiver_user_id;
                        $data[$key1]['message'] = $msg->message;
                        $data[$key1]['created_at'] = $msg->created_at;
                        $data[$key1]['sender'] = $msg->sender;
                    }
                }
            }

            if (count($data) > 0) {
                $response['find'] = 1;

                 $html = View::make('Result.partials.chat_notification', compact('user', 'data'));
                 $response['html'] = $html->render();
               /* $update_all = SocialUserDirectMessage::where('receiver_delete', 0)
                    ->where('receiver_user_id', $user->account_id)
                    ->where('sender_user_id', $friend->id)
                    ->where('seen', 0)
                    ->update(['seen' => 1]);*/
            }else{
                $response['find'] = 0;
            }

        return Response::json($response);
    }
}
