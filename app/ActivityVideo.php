<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ActivityVideo extends Model{

    protected $table = 'activity_videos';

    protected $guarded = [];

    public function workout(){
        return $this->belongsTo('App\AbWorkout','workout_id');
    }

    public function videoMovements(){
        return $this->hasMany('App\ActivityVideoMovement','activity_video_id');
    }
    
}