<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ActivityVideoMovement extends Model{

    protected $table = 'activity_video_movements';

    protected $guarded = [];

    public function actvityVideo(){
        return $this->belongsTo('App\ActivityVideo','activity_video_id');
    }
    
}