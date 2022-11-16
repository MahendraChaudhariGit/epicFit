<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ClientMenu;
use App\Clients;
use App\SocialFriend;
use Auth;
use DB;
use View;
use Response;
use App\SocialPost;

class SocialNetworkController extends Controller
{
    public function check_permission()
    {
        if(Auth::user()->account_type == 'Client') {
            $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
        }
        if(in_array('epic_social', $clientSelectedMenus)){
            return true;
        }else{
            return false;
        }
        
    }

    public function index(Request $request, $client_id=''){

        if($this->check_permission()){
            $user_id = Auth::user()->account_id;
            $client_profile = Clients::select('id','profilepic')->where('id',$user_id)->first();
            // $client_not_in = SocialFriend::where('client_id',Auth::user()->account_id)->pluck('added_client_id')->toArray();
            // array_push($client_not_in,Auth::user()->account_id);
            $client_not_in = [Auth::user()->account_id];
            $search_id = '';
            $data = null;
            if($request->client_id){
                $id= $request->client_id;
                $search_id = $request->client_id;
                $client = Clients::select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','cover_image','about_me','address_city','privacy')
                ->with(['social_friend'=>function($query){
                    $query->where('client_id',Auth::user()->account_id);
                }])
                ->find($search_id);
                // $send_request = SocialFriend::with('clients')->where('client_id',$search_id)->where('status','No Action')->get();
                $send_request_accepred = SocialFriend::where('client_id',$search_id)->where('status','Accepted')->pluck('added_client_id')->toArray();
                $recieve_request_accepted = SocialFriend::where('added_client_id',$search_id)->where('status','Accepted')->pluck('client_id')->toArray();
                $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();
                $all_friends = array_merge($send_request_accepred,$recieve_request_accepted,$epichq);
                $data = Clients::select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','cover_image','about_me','address_city','privacy')
                ->whereIn('id',$all_friends)->get();
                // dd($client);
            }elseif(!empty($client_id)){
                $id = $client_id;
                $client = Clients::select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','cover_image','about_me','address_city','privacy')
                ->with(['recieve_friend'=>function($query){
                    $query->where('added_client_id',Auth::user()->account_id);
                }])
                ->find($client_id);
                // $recieve_request = SocialFriend::with('clients_recieve_request')->where('added_client_id',$client_id)->where('status','No Action')->get();

                $send_request_accepred = SocialFriend::where('client_id',$client_id)->where('status','Accepted')->pluck('added_client_id')->toArray();
                $recieve_request_accepted = SocialFriend::where('added_client_id',$client_id)->where('status','Accepted')->pluck('client_id')->toArray();
                $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();
                $all_friends = array_merge($send_request_accepred,$recieve_request_accepted,$epichq);
                $data = Clients::select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','cover_image','about_me','address_city','privacy')
                ->whereIn('id',$all_friends)->get();
                // dd($client);
            }else{
                $id = $user_id ;
                $client = Clients::select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','cover_image','about_me','address_city','privacy')
                ->with(['request_send','request_recieve'])
                ->find(Auth::user()->account_id);
                $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('added_client_id')->toArray();
                $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('client_id')->toArray();
                $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();

                $all_friends = array_merge($send_request_accepred,$recieve_request_accepted,$epichq);
                // $all_friends = array_merge($all_friends,$epichq);
                
                $data = Clients::select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','cover_image','about_me','address_city','privacy')
                ->whereIn('id',$all_friends)->get();
            }
            //  $all_clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->OfBusiness()->where('account_status','Active')->whereNotIn('id',$client_not_in)->get()->toArray();
             if($request->tab == 'my_post' || !empty($request->client_id) || $request->path() == 'social/my/friend/'.$client_id){
                $posts = SocialPost::with(['social_post_image','social_post_video','client'=>function($q){
                    $q->select('id','firstname','lastname','profilepic');
                },'goal_client'=>function($q){
                    $q->select('id','firstname','lastname','profilepic');
                 },
                ])->where('client_id',$id)
                // ->orWhere('goal_friend_id',$id)
                ->orderBY('id', 'DESC')
                 ->take(100)
                ->get();
             }else{
                $posts = SocialPost::with(['social_post_image','social_post_video','client'=>function($q){
                    $q->select('id','firstname','lastname','profilepic');
                },'goal_client'=>function($q){
                    $q->select('id','firstname','lastname','profilepic');
                 },
                ])->whereIn('client_id', array_merge($all_friends,[$user_id]))
                ->orWhere('goal_friend_id',$id)
                 ->take(100)
                ->orderBY('id', 'DESC')->get();
             }
             $my_image_video_post = SocialPost::with(['social_post_image','social_post_video'])->where('client_id',$id)->orderBY('id', 'DESC')->get();
             $total_image = 0;
             foreach($my_image_video_post->where('social_post_image','!=','[]')->toArray() as $post){
                $total_image += count($post['social_post_image']);
             }
             $comment_count = 2;
            return view('Result.social-network.index',compact('data','client','my_image_video_post','posts','search_id','user_id','client_id','client_profile','comment_count','total_image'));
        }else{
            return redirect('new-dashboard');
        }
    }

    public function direct_message(){
        if($this->check_permission()){
            return view('Result.social-network.direct-message');
        }else{
            return redirect('new-dashboard');
        }
    }

    public function add_friend(Request $request,$added_client_id){
        if($this->check_permission()){
            $sended = SocialFriend::where('client_id',Auth::user()->account_id)->where('added_client_id',$added_client_id)->first();
            $requested = SocialFriend::where('client_id',$added_client_id)->where('added_client_id',Auth::user()->account_id)->first();
            if(isset($requested)){
                $data = $requested;
            }elseif(isset($sended)){
                $data = $sended;
            }else{
                $data = null;
            }
            // $data = SocialFriend::where('client_id',Auth::user()->account_id)->where('added_client_id',$added_client_id)->first();
            if(!isset($data)){
            // dd($added_client_id);
            // foreach(explode(',',$request->added_client_id) as $added_client_id){
                $save = SocialFriend::create([
                    'client_id' => Auth::user()->account_id,
                    'added_client_id' => $added_client_id,
                    'status' => 'No Action',
                    'date' => date("Y-m-d"),
                ]);
            // }
            if(isset($save)){
                $response = [
                    'status' => 'success',
                    'message' => 'Done'
                    // 'message' => 'Friend request sent successfully.'
                ];
            }else{
                $response = [
                    'status' => 'error',
                    'message' => 'Error'
                    // 'message' => 'Friend request not sent, Please try again.'
                ];
            }
        }else{
            $data->update([
                'client_id' => Auth::user()->account_id,
                'added_client_id' => $added_client_id,
                'status' => 'No Action',
                'date' => date("Y-m-d"),
            ]);
            $response = [
                'status' => 'success',
                'message' => 'Done'
                // 'message' => 'Friend request sent successfully.'
            ];
        }
            return response()->json($response);
        }else{
            return redirect('new-dashboard');
        }
    }

    public function cancel_friend(Request $request,$added_client_id){
        if($this->check_permission()){
            $sended = SocialFriend::where('client_id',Auth::user()->account_id)->where('added_client_id',$added_client_id)->first();
            $requested = SocialFriend::where('client_id',$added_client_id)->where('added_client_id',Auth::user()->account_id)->first();
            if(isset($sended)){
                $data = $sended;
            }elseif(isset($requested)){
                $data = $requested;
            }else{
                $data = null;
            }
            // dd($added_client_id,$data);
            if(isset($data)){
                $data->update(['status'=>'Unfriend']);
                $response = [
                    'status' => 'success',
                    'message' => 'Unfriend successfully.'
                ];
            }else{
                $response = [
                    'status' => 'error',
                    'message' => 'Please try again.'
                ];
            }
            return response()->json($response);
        }else{
            return redirect('new-dashboard');
        }
    }

    public function reject_friend(Request $request,$added_client_id){
        if($this->check_permission()){
            // $data = SocialFriend::where('client_id',Auth::user()->account_id)->where('added_client_id',$added_client_id)->first();

            $sended = SocialFriend::where('client_id',Auth::user()->account_id)->where('added_client_id',$added_client_id)->first();
            $requested = SocialFriend::where('client_id',$added_client_id)->where('added_client_id',Auth::user()->account_id)->first();
            if(isset($sended)){
                $data = $sended;
            }elseif(isset($requested)){
                $data = $requested;
            }else{
                $data = null;
            }
            if(isset($data)){
                $data->update([
                    'status' => 'Rejected'
                ]);
                $response = [
                    'status' => 'success',
                    'message' => 'Done'
                    // 'message' => 'Friend request cancel successfully.'
                ];
            }else{
                $response = [
                    'status' => 'error',
                    'message' => 'Error'
                    // 'message' => 'Friend request not cancel, Please try again.'
                ];
            }
            return response()->json($response);
        }else{
            return redirect('new-dashboard');
        }
    }

    public function confirm_friend(Request $request,$added_client_id){
        if($this->check_permission()){
            // $data = SocialFriend::where('client_id',$added_client_id)->where('added_client_id',Auth::user()->account_id)->first();

            $sended = SocialFriend::where('client_id',Auth::user()->account_id)->where('added_client_id',$added_client_id)->first();
            $requested = SocialFriend::where('client_id',$added_client_id)->where('added_client_id',Auth::user()->account_id)->first();
            if(isset($sended)){
                $data = $sended;
            }elseif(isset($requested)){
                $data = $requested;
            }else{
                $data = null;
            }
            if(isset($data)){
                $data->update([
                    'status' => "Accepted"
                ]);
                $response = [
                    'status' => 'success',
                    'message' => 'Done'
                    // 'message' => 'Friend request accepted successfully.'
                ];
            }else{
                $response = [
                    'status' => 'error',
                    'message' => 'Error'
                    // 'message' => 'Friend request not accepted, Please try again.'
                ];
            }
            return response()->json($response);
        }else{
            return redirect('new-dashboard');
        }
    }

    public function update_profile(Request $request){
        // dd($request->all());
        if($this->check_permission()){
            $client = Clients::find($request->client_id);
            if(isset($client)){
                $data = [
                    'gender' => $request->gender,
                    'about_me' => $request->about_me,
                    'birthday' => $request->birthday,
                    'address_city' => $request->address_city,
                    'country' => $request->country,
                ];
                // dd($data);
                $client->update($data);
            }
            return redirect('social/home');
        }else{
            return redirect('new-dashboard');
        }
    }

    public function filter_my_friend(Request $request){
        if($this->check_permission()){
            $search = $request->search;
            $client = Clients::select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','cover_image','about_me','address_city','privacy')
                ->find(Auth::user()->account_id);
            $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('added_client_id')->toArray();
            $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('client_id')->toArray();
            $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();
            $all_friends = array_merge($send_request_accepred,$recieve_request_accepted,$epichq);
            $data = Clients::select('id','business_id','firstname','lastname','email','phonenumber','address1','gender','birthday','country','profilepic','cover_image','about_me','address_city','privacy')
                    ->whereIn('id',$all_friends)->where(function($query) use($search){
                    $query->where('firstname', 'like', '%' . $search . '%')
                     ->orWhere('lastname', 'like', '%' . $search . '%');
                })->get();
            // dd($data);
            return view('Result.social-network.filter-my-friends',compact('data','client'));
            // return response()->json($data);
        }else{
            return redirect('new-dashboard');
        }
    }

    public function filter_requested_friend(Request $request){
        if($this->check_permission()){
            $search = $request->search;
            $client = SocialFriend::with('clients_recieve_request')->whereHas('clients_recieve_request',function($query) use($search){
                $query->where('firstname', 'like', '%' . $search . '%')
                        ->orWhere('lastname', 'like', '%' . $search . '%');
            })->where('added_client_id',Auth::user()->account_id)->where('status','No Action')->get();
            return view('Result.social-network.filter-requested-friend',compact('client'));
        }else{
            return redirect('new-dashboard');
        }
    }

    public function filter_sended_friend(Request $request){
        if($this->check_permission()){
            $search = $request->search;
            $client = SocialFriend::with('clients')->whereHas('clients',function($query) use($search){
                $query->where('firstname', 'like', '%' . $search . '%')
                        ->orWhere('lastname', 'like', '%' . $search . '%');
            })->where('client_id',Auth::user()->account_id)->where('status','No Action')->get();
            return view('Result.social-network.filter-sended-friend',compact('client'));
        }else{
            return redirect('new-dashboard');
        }
    }

    public function coverImage(Request $request){
        $client = Clients::select('id','business_id','cover_image')->find($request->client_id);
        // dd($request->all(),$client);
        if ($request->hasFile('cover_image')) {     
            $file = $request->file('cover_image');
            $file_name = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path() . '/uploads/';
            $upload = $file->move($destinationPath, 'thumb_'.$file_name);
        }
        if(isset($client)){
            $client->update(['cover_image'=>$file_name]);
            $data = [
                'status' => 'success',
                'message' => 'Done',
                'data' => $file_name
            ];
            
        }else{
            $data = [
                'status' => 'error',
                'message' => 'Please try again.',
            ];
        }
        return response()->json($data);
    }

    public function profileImage(Request $request){
        $client = Clients::select('id','business_id','profilepic')->find($request->client_id);
        // dd($request->all(),$client);
        if ($request->hasFile('profile_image')) {     
            $file = $request->file('profile_image');
            $file_name = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path() . '/uploads/';
            $upload = $file->move($destinationPath, 'thumb_'.$file_name);
        }
        if(isset($client)){
            $client->update(['profilepic'=>$file_name]);
            $data = [
                'status' => 'success',
                'message' => 'Done',
                'data' => $file_name
            ];
            
        }else{
            $data = [
                'status' => 'error',
                'message' => 'Please try again.',
            ];
        }
        return response()->json($data);
    }

    public function privacy(Request $request){
        $client_id = $request->client_id;
        $privacy = $request->privacy;
        $client = Clients::select('id','privacy')->find($client_id);
        if(isset($client)){
            $client->update(['privacy'=>$privacy]);
            $responce = [
                'status' => 'success',
                'message' => 'Done',
                'data' => $privacy
            ];
        }else{
            $responce = [
                'status' => 'error',
                'message' => 'Error'
            ];
        }
        return response()->json($responce);
    }

    public function filter_contact(Request $request){

    }

    public function postPreview($id){
        $comment_count = 2; // show all comment
        // $comment_count = 2000000; // show all comment  
        $user_id = Auth::user()->account_id;
        $client_profile = Clients::select('id','profilepic')->where('id',$user_id)->first();
        $post = SocialPost::with(['social_post_image','social_post_video','client'=>function($q){
                    $q->select('id','firstname','lastname','profilepic');
                },'goal_client'=>function($q){
                    $q->select('id','firstname','lastname','profilepic');
                 },
                ])
                ->where('id', $id)
                ->orderBY('id', 'DESC')
                ->first();
        $html = View::make('Result.partials.post_preview', compact('post','user_id', 'comment_count','client_profile'));
        $response['html'] = $html->render();
        return Response::json($response);
    }
}

// $social_post = SocialPost::find($post_id);
// if($social_post){
//     $comment_count = 2000000; // show all comment 
//     $user_id = Auth::user()->account_id;
//     $client_profile = Clients::select('id','profilepic')->where('id',$user_id)->first();
//     $post = SocialPost::with(['social_post_image','client'=>function($q){
//             $q->select('id','firstname','lastname','profilepic');
//            }])->where('id', $post_id)
//            ->orderBY('id', 'DESC')
//            ->first();
       
//     return view('Result.social-network.post.single-post',compact('post', 'user_id', 'comment_count','client_profile'));   
// }