<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MpMealCategory extends Model{
	use SoftDeletes;

    protected $table = 'mpn_meal_categories';
    protected $primaryKey = 'id';
    protected $fillable = ['name','desc'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
}