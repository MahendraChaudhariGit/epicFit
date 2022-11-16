<?php

namespace App\Result\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Meal
 * @package App\Models
 * @version September 17, 2017, 8:49 pm UTC
 *
 * @property string name
 * @property string description
 * @property string main_image
 * @property string thumb_image
 * @property tinyInteger professional
 * @property smallInteger serves
 * @property decimal breads_and_cereals
 * @property decimal fish_and_meat
 * @property decimal milk_and_cheese
 * @property decimal oils_and_nuts
 * @property decimal veg_and_fruit
 */
class Meal extends Model
{
    use SoftDeletes;

    public $table = 'meals';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'description',
        'main_image',
        'thumb_image',
        'professional',
        'serves',
        'breads_and_cereals',
        'fish_and_meat',
        'milk_and_cheese',
        'oils_and_nuts',
        'veg_and_fruit'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'main_image' => 'string',
        'thumb_image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function categories()
    {
    	return $this->belongsToMany('App\Result\Models\Category');
    }

    public function details()
    {
    	return $this->hasOne('App\Result\Models\MealDetail');
    }
}
