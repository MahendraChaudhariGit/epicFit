<?php

namespace App\Result\Models;

use Eloquent as Model;

class MeasurementGalleryImage extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['image','image_name','uploaded_by','created_at','updated_at','deleted_at'];
}
