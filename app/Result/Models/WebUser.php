<?php

namespace App\Result\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WebUser
 * @package App\Models
 * @version September 17, 2017, 5:55 pm UTC
 *
 * @property string web_key
 * @property string session_guid
 * @property string user_guid
 */
class WebUser extends Model
{
    use SoftDeletes;

    public $table = 'web_users';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'web_key',
        'session_guid',
        'user_guid'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'web_key' => 'string',
        'session_guid' => 'string',
        'user_guid' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'web_key' => 'in:test,test2,test3'
    ];

    protected $visible = ['session_guid', 'user_guid'];
}
