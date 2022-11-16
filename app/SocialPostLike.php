<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialPostLike extends Model
{
    protected $guarded = [];

    public function social_post(){
        return $this->belongsTo('App\SocialPost', 'post_id');
    }


    public function client(){
        return $this->belongsTo('App\Client', 'client_id');
    }
}
