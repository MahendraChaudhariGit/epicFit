<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MpMealimages extends Model{

    protected $table = 'mpn_meal_images';
    protected $primaryKey = 'id';
    protected $fillable = ['mmi_img_name'];
	 
}
