<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialPostComment extends Model
{
   protected $guarded = [];

   public function client(){
      return $this->belongsTo('App\Client','client_id','id');
  }

  public function social_post(){
     return $this->belongsTo('App\SocialPost', 'post_id');
  }
    
}
