<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Nahid\Talk\Facades\Talk;
use Auth;
use View;
use Session;
use DB;

class ConversationController extends Controller
{
    protected $authUser;
    public function __construct()
    {
        $this->middleware('auth');
        Talk::setAuthUserId(Auth::user()->id);

        View::composer('user-chat.userlist', function($view) {
            $threads = Talk::threads();
            $view->with(compact('threads'));
        });
    }

    public function index(){
        $loginUser = Auth::user();
        $users = User::where('business_id',$loginUser->business_id)->where('account_type',$loginUser->account_type)->orderBy('name')->where('id','<>',$loginUser->id)->get();
        return view('user-chat.index',compact('users'));
    }

    public function chatHistory($id){
        DB::enableQueryLog();
        $id = (int)$id;
        $conversations = Talk::getMessagesByUserId($id, 0, 1000000);
        
        //dd($conversations);
        $user = '';
        $messages = [];
        if(!$conversations) {
            $user = User::find($id);
        } else {
            $user = $conversations->withUser;
            $messages = $conversations->messages;
        }

        return view('user-chat.conversations', compact('messages', 'user'));
    }

    public function ajaxSendMessage(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'message-data'=>'required',
                '_id'=>'required'
            ];

            $this->validate($request, $rules);

            $body = $request->input('message-data');
            $userId = $request->input('_id');

            if ($message = Talk::sendMessageByUserId($userId, $body)) {
                $html = view('user-chat.newMessageHtml', compact('message'))->render();
                return response()->json(['status'=>'success', 'html'=>$html], 200);
            }
        }
    }

    public function ajaxDeleteMessage(Request $request, $id)
    {
        if ($request->ajax()) {
            if(Talk::deleteMessage($id)) {
                return response()->json(['status'=>'success'], 200);
            }

            return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
        }
    }

    public function tests()
    {
        dd(Talk::channel());
    }
}
