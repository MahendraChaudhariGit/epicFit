<?php

namespace App\Http\Controllers\Result\SocialNetwork;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Clients,SocialFriend,SocialPost,SocialUserDirectMessage};
use Auth;
use DB;
use View;
use Response;
use Image;

class MessageController extends Controller
{
    /* search friend  */
    public function search(Request $request){
            $search = $request->search;
            $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                    ->where('status','Accepted')
                                    ->pluck('added_client_id')
                                    ->toArray();
            $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                        ->where('status','Accepted')
                                        ->pluck('client_id')
                                        ->toArray();
            $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();
            $all_friends = array_merge($send_request_accepred,$recieve_request_accepted, $epichq);

            $search_data = Clients::select('id','firstname','lastname','profilepic')
                    ->whereIn('id',$all_friends)
                    ->where(function($query) use($search){
                        $query->where('firstname', 'like', '%' . $search . '%')
                              ->orWhere('lastname', 'like', '%' . $search . '%');
                     })
                    ->get();
            $html = View::make('Result.social-network.message.seach-friend', compact('search_data'));
            $response['friends'] = $html->render();
            return Response::json($response);       
     }

   /* showing list of friend  */
  public function people_list(Request $request){
        $user = Auth::user();
        $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();
        //check existing 
        $checkEpic = SocialUserDirectMessage::where('sender_user_id', $epichq[0])->where('receiver_user_id', $user->account_id)->count();
        //create contact epichq
        if(!$checkEpic){
            $epic_social = new SocialUserDirectMessage;
            $epic_social->sender_user_id = $epichq[0];
            $epic_social->receiver_user_id = $user->account_id;
            $epic_social->message  = null;
            $epic_social->save();            
        }
        if($checkEpic == 1){
            // $delete_one_msg = SocialUserDirectMessage::where('sender_user_id', $epichq[0])->delete();
            $epic_social = new SocialUserDirectMessage;
            $epic_social->sender_user_id = $epichq[0];
            $epic_social->receiver_user_id = $user->account_id;
            $epic_social->message  = null;
            $epic_social->save();            
        }
        //create contact epichq - end


        // $active_user_id = $request->input('active_user_id');
        $user_list = [];
        $message_list = DB::select( DB::raw("select * from (select * from `social_user_direct_messages` where ((`sender_user_id` = '".$user->account_id."' and `sender_delete` = '0') or (`receiver_user_id` = '".$user->account_id."' and `receiver_delete` = '0')) order by `id` desc limit 200000) as group_table group by receiver_user_id, receiver_user_id order by id desc") );
        $new_list = [];
        foreach(array_reverse($message_list) as $list){
            $msg = new SocialUserDirectMessage();
            $msg->dataImport($list);
            $new_list[] = $msg;
        }
        foreach (array_reverse($new_list) as $message){
            if ($message->sender_user_id == $user->account_id){
                if (array_key_exists($message->receiver_user_id, $user_list)) continue;
                $user_list[$message->receiver_user_id] = [
                    'new' => false,
                    'message' => $message,
                    'client' => $message->receiver
                ];
            }else{
                if (array_key_exists($message->sender_user_id, $user_list)) continue;
                $user_list[$message->sender_user_id] = [
                    'new' => ($message->seen == 0)?true:false,
                    'message' => $message,
                    'client' => $message->sender
                ];
            }
        }
        $response['status'] = 'success';
        $html = View::make('Result.social-network.message.people_list', compact('user', 'user_list'));
        $response['html'] = $html->render();
        return Response::json($response);

    }

  /* showing chat */ 
    public function chat(Request $request){
        $friend = clients::find($request->input('id'));
        $user = Auth::user();
        if ($friend){
            $response['status'] = 'success';
            $message_list = SocialUserDirectMessage::where(function ($q) use($friend, $user){
                   $q->where(function ($q) use($friend, $user){
                    $q->where('sender_user_id', $user->account_id)
                      ->where('receiver_user_id', $friend->id)
                      ->where('sender_delete', 0);
                })->orWhere(function ($q) use($friend, $user){
                    $q->where('receiver_user_id', $user->account_id)
                      ->where('sender_user_id', $friend->id)
                      ->where('receiver_delete', 0);
                });
            })->orderBy('id', 'DESC')->limit(50);
            $update_all = SocialUserDirectMessage::where('receiver_delete', 0)
                        ->where('receiver_user_id', $user->account_id)
                        ->where('sender_user_id', $friend->id)
                        ->where('seen', 0)
                        ->update(['seen' => 1]);
            // $can_send_message = true;
            // if ($user->messagePeopleList()->where('follower_user_id', $friend->id)->count() == 0){
            //     $can_send_message = false;
            // }
            $html = View::make('Result.social-network.message.chat', compact('user', 'friend', 'message_list'));
            $response['html'] = $html->render();
        }
        return Response::json($response);
    }



    public function send(Request $request){
        $friend = Clients::find($request->input('id'));
        $user = Auth::user();
        if ($friend){
            $message = new SocialUserDirectMessage();
            $message->sender_user_id = $user->account_id;
            $message->receiver_user_id = $friend->id;
            $message->message = $request->input('message');
            if ($message->save()){

                event(new \App\Events\SendMessage());

                $response['status'] = 'success';
                $html = View::make('Result.social-network.message.single_message', compact('user', 'message'));
                $response['html'] = $html->render();
                $response['message_id'] = $message->id;
            }
        }

        return Response::json($response);
    }

    public function sendFile(Request $request)
    {
     
        request()->validate([
            'file' => 'required|max:10240', //image|mimes:jpeg,png,jpg,gif,svg|max:2048
        ]);
        
        $friend = Clients::find($request->input('user_id_form'));
        $user = Auth::user();

        if ($files = $request->file('file')) {
          
            $destinationPath = public_path('/uploads/'); // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            /* thumbnail */
            if($request->file_type == 'image'){
                $thumb = Image::make(public_path('/uploads/'.$profileImage))->resize(176, 136, function ($constraint) {
                    $constraint->aspectRatio(); //maintain image ratio
                });
                $thumb->save($destinationPath.'thumb_'.$profileImage);
            }
            /* end  */

            $input['sender_user_id'] = $user->account_id;
            $input['receiver_user_id']  = $friend->id;
            $input['file']  = $profileImage;
            $input['file_type']  = $request->file_type;
            $input['message']  = $request->message;
      
            // $input['thumbnail']  = 'thumb_'.$profileImage;
            $message = SocialUserDirectMessage::create($input);
            if ($message){

                event(new \App\Events\SendMessage());
                
                $response['status'] = 'success';
                $html = View::make('Result.social-network.message.single_message', compact('user', 'message'));
                $response['html'] = $html->render();
                $response['message_id'] = $message->id;

                return Response::json($response);
            }
        }
    }


    
    public function delete_message(Request $request){
        $message = SocialUserDirectMessage::find($request->input('message_id'));
        $user = Auth::user();
        if ($message){
            if ($message->sender_user_id == $user->account_id){
                $message->sender_delete = 1;
            }else{
                $message->receiver_delete = 1;
            }
            if ($message->save()){
                $response['status'] = 'success';
            }
        }
        return Response::json($response);
    }


    public function new_messages(Request $request){
        $friend = Clients::find($request->input('id'));
        $user = Auth::user();
        if ($friend){
            $response['status'] = 'success';
            $message_list = SocialUserDirectMessage::where('receiver_delete', 0)
                ->where('receiver_user_id', $user->account_id)
                ->where('sender_user_id', $friend->id)
                ->where('seen', '0')
                ->orderBy('id', 'DESC')
                ->limit(20);
            if ($message_list->count() > 0) {
                $response['find'] = 1;
                $html = View::make('Result.social-network.message.new-message', compact('user', 'friend', 'message_list'));
                $response['html'] = $html->render();
                $update_all = SocialUserDirectMessage::where('receiver_delete', 0)
                    ->where('receiver_user_id', $user->account_id)
                    ->where('sender_user_id', $friend->id)
                    ->where('seen', 0)
                    ->update(['seen' => 1]);
            }else{
                $response['find'] = 0;
            }
        }

        return Response::json($response);
    }


    public function all_message(){
        return view('Result.social-network.message.all-message');
    }


    public function filter_contact(Request $request){

        $search = $request->search;
        $status = ['Active','Contra'];
        $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                ->where('status','Accepted')
                                ->pluck('added_client_id')
                                ->toArray();
        $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                    ->where('status','Accepted')
                                    ->pluck('client_id')
                                    ->toArray();
        $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();
        $all_friends = array_merge($send_request_accepred,$recieve_request_accepted, $epichq);
       

        // $search_data = Clients::select('id','firstname','lastname','profilepic')
        //         ->whereIn('id',$all_friends)
        //         ->where(function($query) use($search){
        //             $query->where('firstname', 'like', '%' . $search . '%')
        //                   ->orWhere('lastname', 'like', '%' . $search . '%');
        //          })
        //         ->get();

        $search_data = Clients::select('id','firstname','lastname','profilepic')
                       ->where(\DB::raw('concat(firstname," ",lastname)'), 'like', '%'.$search.'%')
                        // ->OfBusiness()
                        ->whereIn('id',$all_friends)
                        ->whereIn('account_status',$status)
                        ->where('id','!=',$auth_id)
                        ->get();
                
        $user = Auth::user();
        $user_list = [];
        $message_list = DB::select( DB::raw("select * from (select * from `social_user_direct_messages` where ((`sender_user_id` = '".$user->account_id."' and `sender_delete` = '0') or (`receiver_user_id` = '".$user->account_id."' and `receiver_delete` = '0')) order by `id` desc limit 200000) as group_table group by receiver_user_id, receiver_user_id order by id desc") );
        $new_list = [];
        foreach(array_reverse($message_list) as $list){
            foreach($search_data as $search){
                if($list->sender_user_id == $search->id || $list->receiver_user_id == $search->id ){
                    $msg = new SocialUserDirectMessage();  
                    $msg->dataImport($list);
                    $new_list[] = $msg;
                } 
            }      
        }
        foreach (array_reverse($new_list) as $message){
            if ($message->sender_user_id == $user->account_id){
                if (array_key_exists($message->receiver_user_id, $user_list)) continue;
                $user_list[$message->receiver_user_id] = [
                    'new' => false,
                    'message' => $message,
                    'client' => $message->receiver
                ];
            }else{
                if (array_key_exists($message->sender_user_id, $user_list)) continue;
                $user_list[$message->sender_user_id] = [
                    'new' => ($message->seen == 0)?true:false,
                    'message' => $message,
                    'client' => $message->sender
                ];
            }
        }
        $response['status'] = 'success';
        $html = View::make('Result.social-network.message.people_list', compact('user', 'user_list'));
        $response['html'] = $html->render();
        return Response::json($response);

    }


    public function chat_list(Request $request){

        $search = $request->search;
        $user = Auth::user();
        $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();
        //check existing 
        $checkEpic = SocialUserDirectMessage::where('sender_user_id', $epichq[0])->where('receiver_user_id', $user->account_id)->count();
        //create contact epichq
        if(!$checkEpic){
            $epic_social = new SocialUserDirectMessage;
            $epic_social->sender_user_id = $epichq[0];
            $epic_social->receiver_user_id = $user->account_id;
            $epic_social->message  = null;
            $epic_social->save();            
         }

         if($checkEpic == 1){
            $delete_one_msg = SocialUserDirectMessage::where('sender_user_id', $epichq[0])->delete();
            $epic_social = new SocialUserDirectMessage;
            $epic_social->sender_user_id = $epichq[0];
            $epic_social->receiver_user_id = $user->account_id;
            $epic_social->message  = null;
            $epic_social->save();            
         }
        //create contact epichq - end
        if($search){
            $status = ['Active','Contra'];
            $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                    ->where('status','Accepted')
                                    ->pluck('added_client_id')
                                    ->toArray();
            $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                        ->where('status','Accepted')
                                        ->pluck('client_id')
                                        ->toArray();
            $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();
            $all_friends = array_merge($send_request_accepred,$recieve_request_accepted, $epichq);

            $search_data = Clients::select('id','firstname','lastname','profilepic')
                        ->where(\DB::raw('concat(firstname," ",lastname)'), 'like', '%'.$search.'%')
                            // ->OfBusiness()
                            ->whereIn('id',$all_friends)
                            ->whereIn('account_status',$status)
                            ->where('id','!=',$user->account_id)
                            ->get();
          }       
        
        $user_list = [];
        $message_list = DB::select( DB::raw("select * from (select * from `social_user_direct_messages` where ((`sender_user_id` = '".$user->account_id."' and `sender_delete` = '0') or (`receiver_user_id` = '".$user->account_id."' and `receiver_delete` = '0')) order by `id` desc limit 200000) as group_table group by receiver_user_id, receiver_user_id order by id desc") );
        $new_list = [];
        if($search){
       
            foreach(array_reverse($message_list) as $list){
                foreach($search_data as $search){
                    if($list->sender_user_id == $search->id || $list->receiver_user_id == $search->id ){
                        $msg = new SocialUserDirectMessage();  
                        $msg->dataImport($list);
                        $new_list[] = $msg;
                    } 
                }      
             }
        } else {
  
            foreach(array_reverse($message_list) as $list){
                        $msg = new SocialUserDirectMessage();  
                        $msg->dataImport($list);
                        $new_list[] = $msg;         
             }

        }
      
        foreach (array_reverse($new_list) as $message){
            if ($message->sender_user_id == $user->account_id){
                if (array_key_exists($message->receiver_user_id, $user_list)) continue;
                $user_list[$message->receiver_user_id] = [
                    'new' => false,
                    'message' => $message,
                    'client' => $message->receiver
                ];
            }else{
                if (array_key_exists($message->sender_user_id, $user_list)) continue;
                $user_list[$message->sender_user_id] = [
                    'new' => ($message->seen == 0)?true:false,
                    'message' => $message,
                    'client' => $message->sender
                ];
            }
        }
    
     

        /*  */
      
        $user = Auth::user();
        if(array_values($user_list)[0]['message']['receiver_user_id'] != $user->account_id){
            $user_id =  (array_values($user_list)[0]['message']['receiver_user_id']); 
        } else {
            $user_id =  (array_values($user_list)[0]['message']['sender_user_id']); 
        }
        $friend = clients::find( $user_id);
        if ($friend){
            $response['status'] = 'success';
            $message_list = SocialUserDirectMessage::where(function ($q) use($friend, $user){
                   $q->where(function ($q) use($friend, $user){
                    $q->where('sender_user_id', $user->account_id)
                      ->where('receiver_user_id', $friend->id)
                      ->where('sender_delete', 0);
                })->orWhere(function ($q) use($friend, $user){
                    $q->where('receiver_user_id', $user->account_id)
                      ->where('sender_user_id', $friend->id)
                      ->where('receiver_delete', 0);
                });
            })->orderBy('id', 'DESC')->limit(50);
            $update_all = SocialUserDirectMessage::where('receiver_delete', 0)
                        ->where('receiver_user_id', $user->account_id)
                        ->where('sender_user_id', $friend->id)
                        ->where('seen', 0)
                        ->update(['seen' => 1]);
            $html = View::make('Result.social-network.message.chat', compact('user', 'friend', 'message_list'));
            $response['single_chat_html'] = $html->render();
        }

        /*  */
        // $show_chat = $this->single_chat($reciever_id);
       
        $response['status'] = 'success';
        $html = View::make('Result.social-network.message.chat-message', compact('user', 'user_list'));
        $response['html'] = $html->render();
        return Response::json($response);

    }


    // public function single_chat(Request $request){
    //     $friend = clients::find($request->input('id'));
    
    public function single_chat($id){
        $friend = clients::find($id);
        $user = Auth::user();
        if ($friend){
            $response['status'] = 'success';
            $message_list = SocialUserDirectMessage::where(function ($q) use($friend, $user){
                   $q->where(function ($q) use($friend, $user){
                    $q->where('sender_user_id', $user->account_id)
                      ->where('receiver_user_id', $friend->id)
                      ->where('sender_delete', 0);
                })->orWhere(function ($q) use($friend, $user){
                    $q->where('receiver_user_id', $user->account_id)
                      ->where('sender_user_id', $friend->id)
                      ->where('receiver_delete', 0);
                });
            })->orderBy('id', 'DESC')->limit(50);
            $update_all = SocialUserDirectMessage::where('receiver_delete', 0)
                        ->where('receiver_user_id', $user->account_id)
                        ->where('sender_user_id', $friend->id)
                        ->where('seen', 0)
                        ->update(['seen' => 1]);
            $html = View::make('Result.social-network.message.chat', compact('user', 'friend', 'message_list'));
            $response['html'] = $html->render();
        }
        return Response::json($response);
    }

    /* end  */
   
}
