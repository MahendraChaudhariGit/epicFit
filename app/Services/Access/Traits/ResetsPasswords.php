<?php

namespace App\Services\Access\Traits;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Frontend\Auth\ResetPasswordRequest;
use App\Http\Requests\Frontend\Auth\SendResetLinkEmailRequest;
use App\Models\Access\User\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DB;

/**
 * Class ResetsPasswords
 * @package App\Services\Access\Traits
 */
trait ResetsPasswords
{
    use RedirectsUsers;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('frontend.auth.passwords.email');
    }

    /**
     * @param SendResetLinkEmailRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(SendResetLinkEmailRequest $request)
    {
        /*$response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(trans('strings.emails.auth.password_reset_subject'));
        });*/
		$response = Password::sendResetLink(['email' => $request->uname], function (Message $message) {
            $message->subject(trans('strings.emails.auth.password_reset_subject'));
        });
        $data = DB::table('password_resets')->where('email',$request->uname)->first();
        $message = "<!DOCTYPE html>
                    <html lang='en-US'>
                    <head>
                    </head>
                    <body>
                        <h2>Reset Password Notification</h2>
                        <div>
                            Hi ". $request->uname.", <br>
                            You are receiving this email because we received a password reset request for your account.
                            <a href='".url('/password/reset/'.$data->token)."'>Click here to reset password</a>
                            <p> This password reset link will expire in :".config('auth.passwords.'.config('auth.defaults.passwords').'.expire')." minutes.</p>
                            <p>If you did not request a password reset, no further action is required.</p>
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
            $mail->Subject = "Reset Password";
            $mail->MsgHTML($message);
            $mail->addAddress($request->uname, 'Test');
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

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));

            case Password::INVALID_USER:
				return redirect()->back()
                    /*->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);*/
					->withInput(['email' => $request->uname])
                    ->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * @param null $token
     * @return $this
     */
    public function showResetForm($token = null)
    {
        if (is_null($token)) {
            return $this->showLinkRequestForm();
        }

        return view('frontend.auth.passwords.reset')
            ->with('token', $token);
    }

    /**
     * @param ResetPasswordRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        /*$credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );*/
		$credentials = ['email' => $request->uname, 'password' => $request->password, 'password_confirmation' => $request->password_confirmation, 'token' => $request->token];
        $ifExists = DB::table('password_resets')->where('email',$credentials['email'])->where('token',$credentials['token'])->exists();
        if($ifExists){
            $user = User::where('email',$credentials['email'])->first();
            if($user){
                $this->resetPassword($user, $credentials['password']);
                return redirect('/login')->with('flash_success', "Password reset successfully");
            }
        }else{
            return redirect()->back()
                    /*->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);*/
					->withInput(['email' => $request->uname])
                    ->withErrors(['email' => 'Invalid Token']);
        }
    }

    /**
     * @param $user
     * @param $password
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();

        // auth()->login($user);
    }
}
