<?php

namespace App\Result\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tip
 * @package App\Models
 * @version September 17, 2017, 11:11 pm UTC
 *
 * @property string description
 * @property string image
 * @property string type
 */
class Tip extends Model
{
    use SoftDeletes;

    public $table = 'tips';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'description',
        'image',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'string',
        'image' => 'string',
        'type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected $visible = ['description', 'image', 'associations'];

    public function associations()
    {
    	return $this->belongsToMany('App\Result\Models\Association');
    }
}
