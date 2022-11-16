<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeReview extends Model
{
    protected $guarded = [];

    private $upvote_count = null;

    public function client(){
        return $this->belongsTo('App\Clients','client_id','id');
    }

    public function replyRecipeReview(){
        return $this->hasMany('App\ReplyRecipeReview','recipe_review_id');
    }

    public function reviewUpvote(){
        return $this->hasMany('App\ReviewUpvote', 'review_id', 'id')->where('type','Review');
     }

    public function getUpvoteCount(){
        if ($this->upvote_count == null){
            $this->upvote_count = $this->reviewUpvote()->count();
        }
        return $this->upvote_count;
     }
}
