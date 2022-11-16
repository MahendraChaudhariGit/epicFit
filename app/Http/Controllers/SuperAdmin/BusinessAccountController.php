<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Access\User\User;
use App\UserLimit;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Http\Traits\HelperTrait;
use DB;
use Session;
class BusinessAccountController extends Controller
{
    use HelperTrait;

    private $cookieSlug = 'contact';

	public function index(Request $request){
        $search = $request->get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        if($search){
            $businessAccounts = User::where(function($query) use($search){
                            $query->orWhere(DB::raw('concat(name," ",last_name)'), 'like', "%$search%")
                                  ->orWhere('email', 'like', "%$search%");
                        })
                        ->where('account_type','!=','Super Admin')
                        ->orderBy('id', 'desc')
                        ->paginate($length);
        }
        else
            $businessAccounts = User::where('account_id','0')->orderBy('id', 'desc')->where('account_type','!=','Super Admin')->paginate($length);
		return view('super-admin.business-account.index',compact('businessAccounts'));
	}

	public function edit($id){
		$businessAccount = User::find($id);
        $userLimits = UserLimit::orderBy('maximum_users','asc')->get();
		return view('super-admin.business-account.edit',compact('businessAccount','userLimits'));
	}

	public function update(Request $request,$id){
		$this->validate($request,[
			'name' => 'required',
			'last_name' => 'required',
			'email' => 'required',
			'web_url' => 'required',
			'telephone' => 'required',
			'referral' => 'required',
            'confirmed' => 'required'
		]);
        
        $request->merge([
            'web_url' => end(explode('/',$request->web_url)),
            'telephone' => $request->country_code.$request->telephone
        ]);
        $requestData = $request->all();
		$businessAccount = User::find($id);
        $oldAccountStatus = $businessAccount->confirmed;
        $newAccountStatus = $requestData['confirmed'];
		try{
			$businessAccount->update($requestData);
            if($newAccountStatus == '1' && $oldAccountStatus != '1'){
                $this->sendConfirmationMailToUser($businessAccount);
            }
            if($newAccountStatus == '2' && $oldAccountStatus != '2'){
                $this->sendConfirmationMailToUser($businessAccount);
            }
			return redirect()->route('superadmin.businessAccount.index')->with('message', 'success|Data has been updated successfully.');
		} catch (\Throwable $e){
			return redirect()->back()->withErrors($e->getMessage());
		}
	}

    public function sendConfirmationEmail($id){
        $businessAccount = User::find($id);
        try{
            $this->sendConfirmationMailToUser($businessAccount);
            return redirect()->route('superadmin.businessAccount.index')->with('message', 'success|Confirmation Email has been sent successfully.');
        } catch (\Throwable $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

	protected function sendConfirmationMailToUser($user){
        // this is temporary mail code which is written in core php.
        $username = $user->name.' '.$user->last_name;
        $confirmationCode = $user->confirmation_code;
        $to = $user->email;
        if($user->confirmed == 0 || $user->confirmed == 2 || $user->confirmed == 3){
            $message = "<!DOCTYPE html>
            <html lang='en-US'>
            <head>
            </head>
            <body>
                <h2>Business Account Status Update</h2>
                <div>
                    Hi ".$username.", <br><br>
                    <p>Just wanted to let you know that our team has started reviewing your business and details.
                    You will be notified for further updates.</p>
                    
                    Thanks! <br>
                    <p>Regards</p>
                    <p><strong>EPIC Trainer Team</strong></p>
                </div>
            </body>
            </html>";
        }
        if($user->confirmed == 1){
        $message = "<!DOCTYPE html>
                    <html lang='en-US'>
                    <head>
                    </head>
                    <body>
                        <h2>Business Account Status Update</h2>
                        <div>
                            Hi ".$username.", <br><br>
                            <p>Your Business account has been approved and published by admin.
                            Find your account details below. Login to your account and setup your business.</p>
                            <table> 
                                <tr>
                                    <td><strong>Login Url:</strong></td>
                                    <td><a href='".url('/')."/login/".$user->web_url."'>".url('/')."/login/".$user->web_url."</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>".$user->email."</td>
                                </tr>
                            </table> 
                            <br>
                            Thanks! <br>
                            <p>Regards</p>
                            <p><strong>EPIC Trainer Team</strong></p>
                        </div>
                    </body>
                    </html>";
        }

        $mail = new PHPMailer(true);
        try {
            // $mail->isSMTP(); // tell to use smtp
            $mail->CharSet = "utf-8"; // set charset to utf8
            $mail->Host = 'epictrainer.com';
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;
            $mail->Port = 25; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'webmaster@epictrainer.com';
            $mail->Password = 'S[WlD3]Tf4*K';
            $mail->setFrom("noreply@epictrainer.com", "EPIC Trainer Team",0);
            $mail->Subject = "Epic Business Account Status Update";
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

    public function delete($id){
        User::where('id',$id)->delete();
        return redirect()->route('superadmin.businessAccount.index')->with('message', 'success|Data has been deleted successfully.');
    }

    public function view($id){
        $businessAccount = User::find($id);
        return view('super-admin.business-account.view',compact('businessAccount'));
    }

}