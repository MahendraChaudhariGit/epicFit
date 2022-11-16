<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChallengeFriend extends Model
{
    Protected $table = 'challenge_friends';
    Protected $guarded = [];

    public function client(){
        return $this->belongsTo('App\Client');
    }

    public function challenge(){
        return $this->belongsTo('App\Challenge');
    }
}
