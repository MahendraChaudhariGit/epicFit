<?php
namespace App\Http\Traits;

//use App\Http\Traits\HelperTrait;
use App\Models\Access\User\User;
/*use Mail;*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

trait TestTrait{
    protected function storeUser($data){
    	$msg = [];
    	$user = new User;
        $user->account_type = $data['type'];
        $user->name = $data['name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
      
        if(array_key_exists('userTypeId', $data))
        	$user->ut_id = (int)$data['userTypeId'];
        else
        	$user->ut_id = 1;

        if(array_key_exists('businessId', $data)){
            $user->business_id = $data['businessId'];
            $user->confirmed = 1;
        }
        else{
            $confirmationCode = $this->generateConfirmationCode();
            $user->confirmation_code = $confirmationCode;
        }

        if(array_key_exists('accountId', $data))
            $user->account_id = (int)$data['accountId'];

        if(array_key_exists('telephone', $data))
            $user->telephone = $data['telephone'];

        if(array_key_exists('address_line_one', $data))
            $user->address_line_one = $data['address_line_one'];
        
        if(array_key_exists('address_line_two', $data))
            $user->address_line_two = $data['address_line_two'];
        
        if(array_key_exists('city', $data))
            $user->city = $data['city'];
        
        if(array_key_exists('country', $data))
            $user->country = $data['country'];
        
        if(array_key_exists('state', $data))
            $user->state = $data['state'];
        
        if(array_key_exists('profile_pic', $data))
            $user->profile_picture = $data['profile_pic'];
        
        if(array_key_exists('postal_code', $data))
            $user->postal_code = $data['postal_code'];
        
        // $user->ut_id = array_key_exists('userTypeId', $data) ? $data['userTypeId'] : 1;

        //$user->confirmed = 1;
        
        $user->save();
        
        if(!array_key_exists('businessId', $data)){
            $this->sendWelcomeMail(['email' => $data['email'], 'password' => $data['password'], 'fullname' => $data['name'].' '.$data['last_name']]);
            $this->sendConfirmationMail($confirmationCode, ['email' => $data['email'], 'fullname' => $data['name'].' '.$data['last_name']]);
        }
        $msg['status'] = 'added';
        $msg['insertId'] = $user->id;

		return json_encode($msg);
    }

    protected function sendWelcomeMail($user){
    	Mail::send('test.welcome_email', ['email' => $user['email'], 'password' => $user['password']], function($message) use ($user) {
			$message->to($user['email'], $user['fullname'])->subject(app_name().': Account Created');
        });
    }

    protected function sendConfirmationMail($confirmationCode, $user){

        // this is temporary mail code which is written in core php.
        $username = $user['fullname'];
        $to = $user['email']; 
        $url = url('')."/verify/".$confirmationCode;
        $message = "<!DOCTYPE html>
                    <html lang='en-US'>
                    <head>
                    </head>
                    <body>
                        <h2>Verify Your Email Address</h2>
                        <div>
                            Hi ".$username." <br>,
                            Please follow the link below to verify your email address.
                            <p> <a href='".$url."'>".$url."</a></p>
                        </div>
                    </body>
                    </html>";
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
            $mail->setFrom("webmaster@epictrainer.com", "EPIC Trainer Team");
            $mail->Subject = "Email Confirmation";
            $mail->MsgHTML($message);
            $mail->addAddress($to, $username);
            $mail->SMTPOptions= array(
                                    'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                    )
                                );

            $mail->send();
        } catch (phpmailerException $e) {
            dd($e);
            //return redirect($this->redirectPath());
        } catch (Exception $e) {
            dd($e);
            //return redirect($this->redirectPath());
        }  

        // this is stable mail code which is written in laravel.
    	/*Mail::send('test.verification_email', ['confirmation_code' => $confirmationCode], function($message) use ($user) {
			$message->to($user['email'], $user['fullname'])->subject(app_name().': Account Verification');
        });*/
    }

    /**
     * temporary code for send mail to admin in core php
     * @param User
     * @return void
    **/
    protected function sendConformationMailToAdmin($user){
       // this is temporary mail code which is written in core php.
        $username = $user->fullname;
        $id= $user->id;
        $from = $user->email; 
        $to = 'pawantri@gmail.com'; 
        $message = "<!DOCTYPE html>
                    <html lang='en-US'>
                    <head>
                    </head>
                    <body>
                        <h2>".$username." is registerd as new user.</h2>
                        <div>
                            EPIC Trainer New User Registerd.<br>
                            Name: ".$username."<br>
                            Email: ".$from."<br>
                            Please follow the link below to approve this user.<br>
                            <a href='".url('')."/business/active/".$id."' target='_blank'>".url('')."/business/active/".$id."</a>
                        </div>
                    </body>
                    </html>";
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
            $mail->setFrom($from, $username);
            $mail->Subject = "EPIC Trainer New User Registerd";
            $mail->MsgHTML($message);
            $mail->addAddress($to, $username);
            $mail->SMTPOptions= array(
                                    'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                    )
                                );

            $mail->send();
        } catch (phpmailerException $e) {
            dd($e);
            //return redirect($this->redirectPath());
        } catch (Exception $e) {
            dd($e);
            //return redirect($this->redirectPath());
        }  
    }

    protected function generateConfirmationCode(){
    	return md5(uniqid(mt_rand(), true));
    }

    protected function entityLogin_tableRecordUpdate($data){ 
       //$user = User::whereAccountId($data['id'])->whereAccountType('Staff')->first();
        $user = $data['entity']->user;
        if($user){
            if(array_key_exists('accountType', $data))
                $user->account_type = $data['accountType'];
            
            if(array_key_exists('firstName', $data))
                $user->name = $data['firstName'];

            if(array_key_exists('lastName', $data))
                $user->last_name = $data['lastName'];

            if(array_key_exists('email', $data))
                $user->email = $data['email'];

            if(array_key_exists('permissionGroupId', $data))
                $user->ut_id = $data['permissionGroupId'];

            if(array_key_exists('password', $data) && $data['password'])
                $user->password = bcrypt($data['password']);
            $user->save();
        }
    }
}