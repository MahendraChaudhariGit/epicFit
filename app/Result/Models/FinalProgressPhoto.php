<?php

namespace App\Result\Models;

use Eloquent as Model;

class FinalProgressPhoto extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['title','only_admin_manage','gallery_id','status','replaced_image','client_id','image','image_type','pose_type','date','created_at','updated_at'];
}
