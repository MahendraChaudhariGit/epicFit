<?php

namespace App\Http\Requests\Frontend\Auth;

use App\Http\Requests\Request;

/**
 * Class RegisterRequest
 * @package App\Http\Requests\Frontend\Access
 */
class RegisterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6',
            'telephone' => 'required|numeric|digits:10',
            'referral' => 'required|max:255',
            'agree' => 'accepted',
            'web_url' => 'unique:users,web_url,NULL,id,deleted_at,NULL|required|max:255',
            'g-recaptcha-response' => 'required',
        ];
    }
	
	public function messages()
	{
		return [
            'agree.accepted' => 'You must agree with our terms and privacy policy.',
            'g-recaptcha-response.required' => 'Captcha field is required.'
		];
	}
}
