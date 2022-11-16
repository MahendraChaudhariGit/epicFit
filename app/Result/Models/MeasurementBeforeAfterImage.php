<?php

namespace App\Result\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeasurementBeforeAfterImage extends Model
{
	use SoftDeletes; 
    protected $primaryKey = 'id';
    protected $fillable = ['title','before_image','after_image','uploaded_by','created_at','updated_at','deleted_at'];
}
