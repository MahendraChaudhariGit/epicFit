<?php

namespace App\Http\Requests\Administration\Access\User;

use App\Http\Requests\Request;

/**
 * Class RestoreUserRequest
 * @package App\Http\Requests\Backend\Access\User
 */
class RestoreUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('restore-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
