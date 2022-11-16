<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\GoalBuddy;
use App\GoalBuddyHabit;
use App\GoalBuddyMilestones;
use App\GoalBuddyTask;
use App\GoalBuddyUpdate;
use App\Clients;
use App\SocialUserDirectMessage;


class GoalNotification extends Controller
{      

	public function emailNotification(){
	    $goalsData = GoalBuddy::with(['goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask'])->where('gb_due_date','>=',date('Y-m-d'))->where('gb_goal_status', 0)->get();
		$epicHqId = Clients::where("about_me", 'epichq')->pluck('id')->toArray();
	    $this->sendGoalNotification($goalsData, $epicHqId);
	    $this->sendMilestoneNotification($goalsData, $epicHqId);
	    $this->sendHabitNotification($goalsData, $epicHqId);
	    $this->sendTaskNotification($goalsData, $epicHqId);
	}

	public function sendGoalNotification($data, $epicHqId){
		foreach ($data as $key => $value) {
			$clientId = $value->gb_client_id;
             if($value->gb_goal_name ==  'Other'){
                  $goal_title = $value->gb_goal_name_other;
			 } else{
				  $goal_title = $value->gb_goal_name ;
			 }
       		$dataToSend = array(
			      	    'parentName'        => $goal_title,
                        'eventTitel'        => $value->gb_goal_name,
                        'eventType'         => 'goal',
                        'eventDueDate'      => $value->gb_due_date,
                        'eventId'           => $value->id,
                    	'clientId'			=> $clientId,
                    	'reminderTime'      => $value->gb_reminder_goal_time,
                        'epicHqCondition'   => $value->gb_reminder_type_epichq,
                        'epicHqId'          => $epicHqId
                    );

				if($value->gb_reminder_type == 'when_overdue'){
					$this->sendReminderOverdue($dataToSend);
				}
				if($value->gb_reminder_type == 'daily'){
					$this->sendReminderDaily($dataToSend);
				}
				if($value->gb_reminder_type == 'weekly'){
					$this->sendReminderWeekly($dataToSend);
				}
				if($value->gb_reminder_type == 'monthly'){
					$this->sendReminderMonthly($dataToSend);
				}
		}
	}

	public function sendMilestoneNotification($data, $epicHqId){
		foreach ($data as $key => $goal) {
			$clientId = $goal->gb_client_id;
			if($goal->gb_goal_name ==  'Other'){
				$goal_title = $goal->gb_goal_name_other;
		   } else{
				$goal_title = $goal->gb_goal_name ;
		   }
			foreach ($goal->goalBuddyMilestones as $key => $value) {
				$dataToSend = array(
                        // 'parentName'        => $goal->gb_goal_name,
						'parentName'        => $goal_title,
                        'eventTitel'        => $value->gb_milestones_name,
                        'eventType'         => 'milestone',
                        'eventDueDate'      => $value->gb_milestones_date,
                        'eventId'           => $value->id,
                    	'clientId'			=> $clientId,
                    	'reminderTime'      => $value->gb_milestones_reminder_time,
                        'epicHqCondition'   => $value->gb_milestones_reminder_epichq,
                        'epicHqId'          => $epicHqId
                    );

					if($value->gb_milestones_reminder == 'when_overdue'){
						$this->sendReminderOverdue($dataToSend);
					}
					if($value->gb_milestones_reminder == 'daily'){
						$this->sendReminderDaily($dataToSend);
					}
					if($value->gb_milestones_reminder == 'weekly'){
						$this->sendReminderWeekly($dataToSend);
					}
					if($value->gb_milestones_reminder == 'monthly'){
						$this->sendReminderMonthly($dataToSend);
					}  
			}
		}
	}

	public function sendHabitNotification($data, $epicHqId){
		foreach ($data as $key => $goal) {
			$clientId = $goal->gb_client_id;
			if($goal->gb_goal_name ==  'Other'){
				$goal_title = $goal->gb_goal_name_other;
		    } else{
				$goal_title = $goal->gb_goal_name ;
		    }
			foreach ($goal->goalBuddyHabit as $key => $value) {
				$dataToSend = array(
                        // 'parentName'        => $goal->gb_goal_name,
						  'parentName'        => $goal_title,
                        'eventTitel'        => $value->gb_habit_name,
                        'eventType'         => 'habit',
                        'eventDueDate'      => $goal->gb_due_date,
                        'eventId'           => $value->id,
                    	'clientId'			=> $clientId,
                    	'reminderTime'      => $value->gb_habit_reminder_time,
                        'epicHqCondition'   => $value->gb_habit_reminder_epichq,
                        'epicHqId'          => $epicHqId
                    );

					if($value->gb_habit_reminder == 'when_overdue'){
						$this->sendReminderOverdue($dataToSend);
					}
					if($value->gb_habit_reminder == 'daily'){
						$this->sendReminderDaily($dataToSend);
					}
					if($value->gb_habit_reminder == 'weekly'){
						$this->sendReminderWeekly($dataToSend);
					}
					if($value->gb_habit_reminder == 'monthly'){
						$this->sendReminderMonthly($dataToSend);
					}  
			}
		}
	}

	public function sendTaskNotification($data, $epicHqId){
		foreach ($data as $key => $goal) {
			$clientId = $goal->gb_client_id;
			if($goal->gb_goal_name ==  'Other'){
				$goal_title = $goal->gb_goal_name_other;
		    } else{
				$goal_title = $goal->gb_goal_name ;
		    }
			foreach ($goal->goalBuddyTask as $key => $value) {
				$dataToSend = array(
                        // 'parentName'        => $goal->gb_goal_name,
                        'parentName'        => $goal_title,
                        'eventTitel'        => $value->gb_task_name,
                        'eventType'         => 'task',
                        'eventDueDate'      => $goal->gb_due_date,
                        'eventId'           => $value->id,
                    	'clientId'			=> $clientId,
                    	'reminderTime'      => $value->gb_task_reminder_time,
                        'epicHqCondition'   => $value->gb_task_reminder_epichq,
                        'epicHqId'          => $epicHqId
                    );

					if($value->gb_task_reminder == 'when_overdue'){
						$this->sendReminderOverdue($dataToSend);
					}
					if($value->gb_task_reminder == 'daily'){
						$this->sendReminderDaily($dataToSend);
					}
					if($value->gb_task_reminder == 'weekly'){
						$this->sendReminderWeekly($dataToSend);
					}
					if($value->gb_task_reminder == 'monthly'){
						$this->sendReminderMonthly($dataToSend);
					}  
			}
		}
	}

	public function sendReminderOverdue($data){
		$dueDate = $data['eventDueDate'];
    	$currentDate = date('Y-m-d');

    	if($currentDate > $dueDate)
    	{
    		$sendReminderDate = date('Y-m-d', strtotime($dueDate . ' +1 day'));
    		if($currentDate == $sendReminderDate)
    		{
				$html = '<p><strong style="color:#f64c1e">'.ucwords($data['eventType']).': </strong>'.$data['eventDueDate'].'</p>';
				$this->prepareHTML($data,$html);
    		}
    	}
	}

	public function sendReminderDaily($data){
		if($data['eventDueDate'] >= date('Y-m-d')){
	 		if($data['reminderTime'] == date('H')) {	
	 			$html = '<p><strong style="color:#f64c1e">'.ucwords($data['eventType']).': </strong>'.$data['eventDueDate'].'</p>';
	 			$this->prepareHTML($data,$html);	
	 		}
	 	}
	}

	public function sendReminderWeekly($data){
		if($data['eventDueDate'] == date('Y-m-d')){
	 		if($data['reminderTime'] == date('D')) {
	 			$html = '<p><strong style="color:#f64c1e">'.ucwords($data['eventType']).': </strong>'.$data['eventDueDate'].'</p>';
	 			$this->prepareHTML($data,$html);	
	 		}
	 	}
	}

	public function sendReminderMonthly($data){
		if($data['eventDueDate'] == date('Y-m-d')){
	 		if($data['reminderTime'] >= date('d')) {
	 			$html = '<p><strong style="color:#f64c1e">'.ucwords($data['eventType']).': </strong>'.$data['eventDueDate'].'</p>';
	 			$this->prepareHTML($data,$html);	
	 		}
	 	}
	}

	public function prepareHTML($value , $html){

   		$data = Clients::where('id', $value['clientId'])->firstOrFail();
   		// $to = "mailtesting206@gmail.com";
        $to = $data->email;
   		$username = $data->firstname;
   		$subject  = ucwords($value['eventType']);
   		$message = $this->getMessage($data , $value, $html);

	 if($value['epicHqCondition'] == 'email-epichq'){
			$this->sendMail($username , $to , $message , $subject);  
		    $this->sendMessage($value, $data);     
	 }

	 // if($value['epicHqCondition'] = 'email'){
	  if($value['epicHqCondition'] == 'epichq'){
		  $this->sendMessage($value, $data);    
	  }
	
	 // if($value['epicHqCondition'] != 'epichq'){
	 if($value['epicHqCondition'] == 'email'){
		   $this->sendMail($username , $to , $message , $subject);    
	   }
   }

    public function getMessage($data, $value, $html){   

    $message = '<!DOCTYPE html>
			<html>
			<head>
				<title>Email</title>
			</head>
			<body>
				<div style="width: 650px;margin:0px auto;border: 1px solid #b5b5b5;box-sizing: border-box;padding:20px;border-radius: 3px;">
					<div style="padding: 15px 0px;font-size: 14px;border-bottom: 1px solid #b5b5b5;background: #e8e8e8;border-radius: 3px;font-family: arial">
						<div style="width: 150px;margin:0px auto;">
							<img src="http://epicfitstudios.com/wp-content/themes/epicfit/assets/images/logo.svg">
						</div>
					</div>
					<div style="padding: 15px 0px;font-size: 14px;font-family: arial">
						<p>Hi <strong>'.$data->firstname.',</strong></p>
					
					</div>
				

						'.$html.'
						
				
					<div style="padding: 15px 0px;font-size: 14px;font-family: arial">
						<p>Please review and complete these activities in order to complete the goal. Let your trainer know if you need any assistance.</p>
					</div>
					<div style="padding: 15px 0px;font-size: 14px;font-family: arial">
						<p style="margin-bottom: 5px">Thanks,</p>
						<p style="margin-top: 0px">Team EPICFIT</p>
					</div>
					<div style="padding: 15px 0px;text-align:center;font-family: arial;border-top: 1px solid #b5b5b5;">
						<p style="font-size: 12px;margin-bottom: 0px">© Epic Fit Fitness Centre – Albany Fitness Classes, 2019</p>
						<p style="font-size: 12px;margin-top: 5px;margin-bottom: 0px;">Studio Alpha 1- Suite 1, 4 Arrenway Drive Albany, Auckland</p>
					</div>
				</div>
			</body>
			</html>';

			return $message;
    }

    public function sendMessage($data, $client){
        if($data['eventTitel'] != ''){
            $messageHtml = $this->getMessageEpic($data, $client);

            if($messageHtml != ''){
                $message = new SocialUserDirectMessage();
                $message->sender_user_id = $data['epicHqId'][0];
                $message->receiver_user_id = $data['clientId'];
                $message->message = $messageHtml;
                if ($message->save()){
                    event(new \App\Events\SendMessage());
                }
            }
			return $message;
        }
    }

    public function sendMail($username , $to , $message , $subject){

        $username = $username;
       	$subject  = $subject;
        $to = $to;
        $message =  $message;

        $mail = new PHPMailer(true);
        try {
            //$mail->isSMTP(); // tell to use smtp
            $mail->CharSet = "utf-8"; // set charset to utf8
            $mail->Host = 'epictrainer.com';
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;
            $mail->Port = 25; // most likely something different for you. This is the mailtrap.io port i use for testing.
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
		 return $result;
        // dd($result);
        } catch (phpmailerException $e) {
            dd($e);
            //return redirect($this->redirectPath());
        } catch (Exception $e) {
            dd($e);
            //return redirect($this->redirectPath());
        }  
    }

       public function getMessageEpic($data, $client){
	
        $messageHtml = "";
        if($data['eventType'] == 'milestone'){
                $messageHtml = "Hi ".$client->firstname.",

Your milestone ".$data['eventTitel']." of goal ".$data['parentName']." is due on ".$data['eventDueDate'].".

Please review and complete these activities in order to complete the goal. Let your trainer know if you need any assistance.

Thanks:
Team EPICFIT";
            }
            
            if($data['eventType'] == 'goal'){
                $messageHtml = "Hi ".$client->firstname.",

Your Goal ".$data['parentName']." is due on ".$data['eventDueDate'].".

Please review and complete these activities in order to complete the goal. Let your trainer know if you need any assistance.

Thanks:
Team EPICFIT";

            }

            if($data['eventType'] == 'habit'){
                $messageHtml = "Hi ".$client->firstname.",

Your Habit ".$data['eventTitel']." of goal ".$data['parentName']." is due on ".$data['eventDueDate'].".

Please review and complete these activities in order to complete the goal. Let your trainer know if you need any assistance.

Thanks:
Team EPICFIT";
            }

            if($data['eventType'] == 'task'){
                $messageHtml = "Hi ".$client->firstname.",

Your Task ".$data['eventTitel']." of goal ".$data['parentName']." is due on ".$data['eventDueDate'].".

Please review and complete these activities in order to complete the goal. Let your trainer know if you need any assistance.

Thanks:
Team EPICFIT";
            }

        return $messageHtml;
    }

}
