<?php

namespace App\Services\Access\Traits;

use Illuminate\Support\Facades\Auth;
use App\Events\Frontend\Auth\UserRegistered;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
/*use Mail;*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * Class RegistersUsers
 * @package App\Services\Access\Traits
 */
trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {
        $notIn = ['mailinator','yopmail'];
        if (in_array(explode('.',explode('@',$request->email)[1])[0],$notIn))
        {
           return redirect()->back()->with('flash_danger','Please use your genuine email ids.');
        }

        $request->merge([
            'telephone' => $request->country_code.$request->telephone
        ]);
        if (config('access.users.confirm_email')) {
            $user = $this->user->create($request->all());
            event(new UserRegistered($user));
            $this->sendConfirmationMailToAdmin($user);
            return redirect('/login')->withFlashSuccess('Thanks for registration. Your account is under review and you will get email notification for updates.');
            // return redirect()->route('frontend.index')->withFlashSuccess(trans('exceptions.frontend.auth.confirmation.sent_approval'));
        } else {
            auth()->login($this->user->create($request->all()));
            event(new UserRegistered(access()->user()));
            return redirect($this->redirectPath());
        }
    }

    /**
     * @param Temprory email send code in core php
     * @return void
     */
    protected function sendConfirmationMailToUser($user){
        // this is temporary mail code which is written in core php.
        $username = $user->name.' '.$user->last_name;
        $confirmationCode = $user->confirmation_code;
        $to = $user->email;
        $message = "<!DOCTYPE html>
                    <html lang='en-US'>
                    <head>
                    </head>
                    <body>
                        <h2>Verify Your Email Address</h2>
                        <div>
                            Hi ".$username.", <br>
                            Please follow the link below to verify your email address.
                            <p> <a href='".url('')."/verify/".$confirmationCode."' target='_blank'>".url('')."/verify/".$confirmationCode."</a></p>
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
            $mail->Port = 25; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'webmaster@epictrainer.com';
            $mail->Password = 'S[WlD3]Tf4*K';
            $mail->setFrom("noreply@epictrainer.com", "EPIC Trainer Team");
            $mail->Subject = "Email Conformation";
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

    protected function sendConfirmationMailToAdmin($user){
        // this is temporary mail code which is written in core php.
        $username = $user->name.' '.$user->last_name;
        $expectations = '';
        if($user->client_management == '1')
            $expectations = $expectations.'Client Management, ';
        if($user->business_support == '1')
            $expectations = $expectations.'Business Support, ';
        if($user->Knowledge == '1')
            $expectations = $expectations.'Knowledge, ';
        if($user->resources == '1')
            $expectations = $expectations.'Resources, ';
        if($user->mentoring == '1')
            $expectations = $expectations.'Mentoring';
        $confirmationCode = $user->confirmation_code;
        $to = 'carlyle@est74.com';
        // $to = 'deepmala@lucidsoftech.in';
        $message = "<!DOCTYPE html>
                    <html lang='en-US'>
                    <head>
                    </head>
                    <body style='font-family:arial'>
                        <h2>New Business Account Created</h2>
                        <div>
                            <p>Following are the details of business account:</p>
                            <table> 
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>".$username."</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>".$user->email."</td>
                                </tr>
                                <tr>
                                    <td><strong>Web Url:</strong></td>
                                    <td>".url('/')."/login/".$user->web_url."</td>
                                </tr>
                                <tr>
                                    <td><strong>Telephone:</strong></td>
                                    <td>".$user->telephone."</td>
                                </tr>
                                <tr>
                                    <td><strong>Referral:</strong></td>
                                    <td>".$user->referral."</td>
                                </tr>
                                <tr>
                                    <td><strong>Expectations:</strong></td>
                                    <td>".$expectations."</td>
                                </tr> 
                            </table>
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
            $mail->Port = 25; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'webmaster@epictrainer.com';
            $mail->Password = 'S[WlD3]Tf4*K';
            $mail->setFrom("noreply@epictrainer.com", "EPIC Trainer Team");
            $mail->Subject = "EPIC New Business Account";
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
}