<?php

namespace App\Http\Requests\Administration\Access\Role;

use App\Http\Requests\Request;

/**
 * Class DeleteRoleRequest
 * @package App\Http\Requests\Backend\Access\Role
 */
class DeleteRoleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-roles');
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
