<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddGalleryCategory extends Model
{
    protected $table = 'add_gallery_categories';

    protected $guarded = [];

    public function gallery_category_list(){
        return $this->hasMany('App\GalleryCategoryList', 'cat_id', 'id');
    }
}
