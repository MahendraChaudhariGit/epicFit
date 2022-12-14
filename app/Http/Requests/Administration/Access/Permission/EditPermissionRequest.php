<?php

namespace App\Http\Requests\Administration\Access\Permission;

use App\Http\Requests\Request;

/**
 * Class EditPermissionRequest
 * @package App\Http\Requests\Backend\Access\Permission
 */
class EditPermissionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-permissions');
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
