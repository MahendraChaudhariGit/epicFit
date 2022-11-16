<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ActivityType;
use App\ChallengeType;
use App\Challenge;
use App\ChallengeFriend;
use App\FitnessMap;
use App\Clients;
use App\SocialFriend;
use DB;
use Auth;
use Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ChallengeController extends Controller
{
    public function create(Request $request,$id,$c_id=''){
        $challenge = null;
        $activity_types = ActivityType::all()->toArray();
        $challenge_types = ChallengeType::all()->toArray();
        $fitness_map = FitnessMap::select('id','name')->find($id);
        $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('added_client_id')->toArray();
        $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('client_id')->toArray();
        $epichq = Clients::where('about_me', 'epichq')->pluck('id')->toArray();         
        $all_friends = array_merge($send_request_accepred,$recieve_request_accepted, $epichq);
        // $all_clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->OfBusiness()->where('account_status','Active')->whereNotIn('id',[Auth::user()->account_id])->get()->toArray();
        $all_clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->OfBusiness()->where('account_status','Active')->whereIn('id',$all_friends)->get()->toArray();
        // dd($all_clients);
        if(!empty($c_id)){
            $challenge = Challenge::with(['challenge_friend'])->where('id',$c_id)->where('client_id', Auth::user()->account_id)->orderBy('id','desc')->first();
            if(empty($challenge)){
                return redirect('epic/train-gain/fitness-mapper');
            }
        }
        return view('Result.challenge.create',compact('id','activity_types','challenge_types','fitness_map','all_clients','challenge'));
    }

    public function save(Request $request){
    //   dd($request->all());
        $data = [
            'client_id' => $request->client_id,
            'challenge_type_id' => $request->challenge_type_id,
            'activity_type_id' => $request->activity_type_id,
            'fitness_mapper_route_id' => $request->fitness_mapper_route_id,
            'name' => $request->name,
            'date' => $request->date,
        ];
        if(!empty($request->challenge_id)){
            $update = Challenge::find($request->challenge_id);
            $challenge_friend = ChallengeFriend::where('challenge_id',$request->challenge_id)
                                ->where('client_id','!=', Auth::user()->account_id)
                                ->pluck('client_id')
                                ->toArray();
            $new_added = array_diff(explode(',',$request->shared_client_id),$challenge_friend);
            $removable = array_diff($challenge_friend,explode(',',$request->shared_client_id));
            // dd($new_added,$removable,$challenge_friend,explode(',',$request->shared_client_id));
            if($update){
                $update->update($data);
                if(count($removable) > 0){
                    foreach($removable as $remove){
                        ChallengeFriend::where('challenge_id',$request->challenge_id)->where('client_id',$remove)->delete();
                    }
                }
                $challenge_owner = ChallengeFriend::where('challenge_id',$request->challenge_id)
                                ->where('client_id',Auth::user()->account_id)
                                ->first();
                 if(!$challenge_owner){
                      ChallengeFriend::create([
                            'challenge_id' => $request->challenge_id,
                            'client_id' => $request->client_id,
                            'status' => 'Accepted',
                            'challenge_by'=>'My challenge'
                        ]);
                  }
                if(count($new_added) > 0){
                    foreach($new_added as $shared_client_id){
                        if($shared_client_id){
                            ChallengeFriend::create([
                                'challenge_id' => $request->challenge_id,
                                'client_id' => $shared_client_id,
                                'status' => 'No',
                                'challenge_by'=>'Via invitation'
                            ]);
                        }
                      
                    }
                 
                    /* mail */
                    if($request->shared_client_id){
                        $this->challenge_mail(implode(',',$new_added));
                    }
                 /* end mail */  

                }
            }
        }else{
            $save = Challenge::create($data);
            if($save->id){
                foreach(explode(',',$request->shared_client_id) as $shared_client_id){
                    if($shared_client_id){
                        $saveChallengeFriend = ChallengeFriend::create([
                            'challenge_id' => $save->id,
                            'client_id' => $shared_client_id,
                            'status' => 'No',
                            'challenge_by'=>'Via invitation'
                        ]);
                    }            
                }
                ChallengeFriend::create([
                    'challenge_id' => $save->id,
                    'client_id' => Auth::user()->account_id,
                    'status' => 'Accepted',
                    'challenge_by'=>'My challenge'
                ]);
                /* email */
                if($request->shared_client_id){
                    $this->challenge_mail($request->shared_client_id);
                }
                /* end mail  */
            }
        }

        return redirect('epic/train-gain/fitness-mapper');
    }

    public function deleteChallenge($id){
        $delete = Challenge::find($id);
        if(isset($delete)){
            ChallengeFriend::where('challenge_id',$delete->id)->delete();
            $delete->delete();
            return response()->json([
                'status'=>'success',
                'message'=>'Challenge deleted successfully.'
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'message'=>'Challenge not found.'
            ]);
        }
    }

    public function challengeCompleted(Request $request){
        // dd($request->all());
        // if($request->challenge_from == 'my-challenge'){
        //     $update = Challenge::find($request->challenge_id);
        //     // if(isset($update)){
        //     //     $update->update(['complete_time'=>$request->time]);
        //     // }
        // }
        // if($request->challenge_from == 'via-invitation'){
        //     $update = ChallengeFriend::where('challenge_id',$delete->id)->where('client_id',$request->client_id)->first();
        //     // if(isset($update)){
        //     //     $update->update(['complete_time'=>$request->time]);
        //     // }
        // }
        $update = ChallengeFriend::where('challenge_id',$request->challenge_id)
                ->where('client_id',$request->client_id)
                ->where('status','Accepted')
                ->first();
        if(isset($update)){
            $update->update(['complete_time'=>$request->time]);
            return response()->json([
                'status'=>'success',
                'message'=>'Challenge time saved successfully.'
            ]);
        } else{
            return response()->json([
                'status'=>'error',
                'message'=>'Challenge not accepted.'
            ]);
        }
      
    }

/* send challenge  Invitation mail */
    // public function challenge_mail($shared_client_id){
    //     $challenge_friend_array = explode(',',$shared_client_id);

    //     $challenge_friend_data = ChallengeFriend::with(['challenge.client'=>function($q){
    //                       $q->select('id','firstname','lastname','email');
    //                     }, 
    //                    'client'=>function($q){
    //                     $q->select('id','firstname','lastname','email');
    //                     },
    //                     'challenge'=>function($query){
    //                         $query->select('id','client_id','name','date');
    //                        }])
    //                 ->whereIn('client_id',$challenge_friend_array)
    //                 ->get(); 

    //     foreach($challenge_friend_data as $challenge_friend_email){
    //             $name = $challenge_friend_email['client']['firstname'];
    //             $email = $challenge_friend_email['client']['email'];
    //            Mail::send('Result.challenge.challenge_mail', compact('challenge_friend_email'), function($message) use($email,$name){
    //               $message->to($email, $name)->subject('Challenge Invitation');
    //          });
    //       }
    //    }

public function challenge_mail($shared_client_id){
        $challenge_friend_array = explode(',',$shared_client_id);
        $challenge_friend_data = ChallengeFriend::with(['challenge.client'=>function($q){
                          $q->select('id','firstname','lastname','email');
                        }, 
                      'client'=>function($q){
                        $q->select('id','firstname','lastname','email');
                        },
                        'challenge'=>function($query){
                            $query->select('id','client_id','name','date');
                          }])
                    ->whereIn('client_id',$challenge_friend_array)
                    ->get(); 
        foreach($challenge_friend_data as $challenge_friend_email){
                 $username = $challenge_friend_email['client']['firstname'].' '.$challenge_friend_email['client']['lastname'];
                 $to = $challenge_friend_email['client']['email'];
                 $subject  = 'Challenge Invitation';
                 $client_data = 'challenge_receiver_id='.$challenge_friend_email[client]['id'] .'&challenge_id='.$challenge_friend_email['challenge_id'].'&name='.$username.'&email='.$challenge_friend_email[client]['email']; 
                 $accept_url = url('/') . '/challenge_invitation?status=accepted&'.$client_data;
                 $reject_url = url('/') . '/challenge_invitation?status=rejected&'.$client_data;
                 $message = view('Result.challenge.challenge_mail', compact('challenge_friend_email','accept_url','reject_url'))->render();
                 $mail = new PHPMailer(true);
                try {
                    //$mail->isSMTP(); // tell to use smtp
                    $mail->CharSet = "utf-8"; // set charset to utf8
                    $mail->Host = 'epictrainer.com';
                    $mail->SMTPAuth = false;
                    $mail->SMTPSecure = false;
                    $mail->Port = 2525; // most likely something different for you. This is the mailtrap.io port i use for testing.
                    $mail->Username = 'webmaster@epictrainer.com';
                    $mail->Password = 'S[WlD3]Tf4*K';
                    $mail->setFrom("noreply@epictrainer.com", "EPIC Trainer Team");
                    $mail->Subject = $subject;
                    $mail->MsgHTML($message);
                    $mail->addAddress($to, $username);
                    $mail->SMTPOptions= array(
                        'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                        )
                    );
                $result =  $mail->send();
                } catch (phpmailerException $e) {
                    dd($e);
                } catch (Exception $e) {
                    dd($e);
                }
            }
       }

 public function challenge_invitation(Request $request){
            $challenge_receiver_name = $request->name; 
            $challenge_receiver_id = $request->challenge_receiver_id;
            $challenge_receiver_email = $request->email;
            $input = $request->all();
      if(Auth::user()->account_id == $challenge_receiver_id &&  Auth::user()->email == $challenge_receiver_email){
            $challenge_friend = ChallengeFriend::with('challenge')
                             ->where('client_id',$challenge_receiver_id)
                             ->where('challenge_id',$request->challenge_id)
                             ->where('status','No')
                             ->first();
            if($challenge_friend){
                $challenge_sender = Clients::select('id','firstname','lastname','email')
                              ->where('id',$challenge_friend['challenge']['client_id'])
                              ->first();
                // if($challenge_friend){
                    if($request->status == 'accepted'){
                        $update =  $challenge_friend->update([
                            'status'=> 'Accepted'
                        ]);
                        $text = $challenge_receiver_name.' accepted your challenge.';
                        $toaster_message = 'Accepted';
                    }
                    if($request->status == 'rejected'){
                        $update =  $challenge_friend->update([
                        'status'=> 'Denied'
                        ]);
                        $text = $challenge_receiver_name.' busy at the moment so unable to participate in your challenge.';
                        $toaster_message = 'Rejected';
                    }
                     $this->challenge_status_mail($challenge_sender, $text, $challenge_receiver_id,$challenge_receiver_email);
                    // } 
                } else {
                    $toaster_message = 'Performed';
                }
                     
                 return redirect('epic/train-gain/fitness-mapper')->with(['page_name' => 'my_challenge', 'message'=> $toaster_message]);
                } else{
                    \Session::put('challenge_data',  $input);
                   return redirect('login')->with(['page_name' => 'my_challenge', 'data'=> $input]);
              } 
            
         }

public function challenge_status_mail($challenge_sender, $text, $challenge_receiver_id, $challenge_receiver_email){
        $username = $challenge_sender['firstname'];
        $to = $challenge_sender['email'];
        $subject  = 'Challenge Status';
        $message = view('Result.challenge.challenge_status', compact('username','text'))->render();
        $mail = new PHPMailer(true);
        try {
            //$mail->isSMTP(); // tell to use smtp
            $mail->CharSet = "utf-8"; // set charset to utf8
            $mail->Host = 'epictrainer.com';
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;
            $mail->Port = 2525; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'webmaster@epictrainer.com';
            $mail->Password = 'S[WlD3]Tf4*K';
            $mail->setFrom("noreply@epictrainer.com", "EPIC Trainer Team");
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            $mail->addAddress($to, $username);
            $mail->SMTPOptions= array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );
         $result =  $mail->send();
        
        } catch (phpmailerException $e) {
            dd($e);
        } catch (Exception $e) {
            dd($e);
        }

    }

/* end  */
}
