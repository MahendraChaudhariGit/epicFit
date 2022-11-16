<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    Protected $table = 'challenges';
    Protected $guarded = [];

    public function challenge_type(){
        return $this->belongsTo('App\ChallengeType','challenge_type_id','id');
    }

    public function activity_type(){
        return $this->belongsTo('App\ActivityType','activity_type_id','id');
    }

    public function fitness_mapper_route(){
        return $this->belongsTo('App\FitnessMap','fitness_mapper_route_id','id');
    }

    public function challenge_friend(){
        return $this->hasMany('App\ChallengeFriend');
    }

    public function client(){
        return $this->belongsTo('App\Client');
    }

    
}
