<?php

namespace App\Result\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MealDetail
 * @package App\Models
 * @version September 18, 2017, 12:07 pm UTC
 *
 * @property integer meal_id
 * @property integer when_eaten_id
 * @property tinyInteger public
 * @property string method
 * @property string ingredients
 * @property string tip
 */
class MealDetail extends Model
{
    use SoftDeletes;

    public $table = 'meal_details';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'meal_id',
        'when_eaten_id',
        'public',
        'method',
        'ingredients',
        'tip'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'meal_id' => 'integer',
        'when_eaten_id' => 'integer',
        'method' => 'string',
        'ingredients' => 'string',
        'tip' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function meal()
    {
    	$this->belongsToOne('App\Result\Model\Meal');
    }
}
