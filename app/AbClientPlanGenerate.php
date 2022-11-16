<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use DB;

class AbClientPlanGenerate extends Model{
    use SoftDeletes;

    protected $table = 'ab_client_plan_generate';
    protected $fillable = ['workout_id','purpose', 'equipment', 'curr_phy_act', 'prev_phy_act', 'next_phy_act', 'curr_intensity_phy_act','created_at','updated_at'];

    public function exercise(){
        return $this->belongsTo('App\Exercise', 'exercise_id');
    }
    
    /** 
     * Boot 
    **/
    protected static function boot(){
        parent::boot();
        static::deleting(function($plan){
            // Delete plan date 
        });
        static::deleted(function(){
            
        });
    }
    
}
