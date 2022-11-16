<?php

namespace App\Result\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Association
 * @package App\Models
 * @version September 17, 2017, 11:12 pm UTC
 *
 * @property string name
 */
class Association extends Model
{
    use SoftDeletes;

    public $table = 'associations';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected $visible = ['name'];
}
