<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Exercise extends Model{
    use SoftDeletes;
        const SHOW = 0,HIDE = 1;


    protected $table = 'exercises';
     
    public function scopeActive(){
            return $this->where('listing_status',$this::SHOW);
        }

    public function resources(){
        return $this->belongsToMany('App\AbResources', 'ab_exercise_resources', 'exercise_id', 'resource_id');
    }	
	public function favourite(){
        return $this->hasMany('App\AbFavouriteExercise','exercise_id');
    }	

    public function workouts(){
        return $this->belongsToMany('App\AbWorkout', 'ab_workout_exercise', 'workout_id', 'exercise_id');
    }

    public function workoutExercises(){
        return $this->hasMany('App\AbWorkoutExercise', 'exercise_id', 'id');
    }

    public function exeimages(){
        return $this->hasMany('App\AbExerciseImage','aei_exercise_id','id');
    } 

    public function exevideos(){
        return $this->hasMany('App\AbExerciseVideo','aei_exercise_id','id');
    } 

    public function filter(){
        return $this->hasOne('App\AbClientPlanGenerate','exercise_id','id');
    }

    /*public function filter(){
        return $this->hasOne('App\AbClientPlanGenerate','exercise_id','id');
    }*/


    public function equipment($id){
        return \App\AbEquipment::where('id', $id)->pluck('eq_name')->first();
    }

    public function ability($id){
        return \App\AbAbility::where('id', $id)->pluck('name')->first();
    }

    public function muscleGroup($string){
        $name = '';
        if($string){
            $ids = explode(',', $string);
            $nameArray = \App\AbMuscleGroup::whereIn('id', $ids)->pluck('name')->toArray();
            if(count($nameArray))
                $name = implode(', ', $nameArray); 
        }
        return $name; 
    }

    public function exerciseType($id){
        return \App\AbExerciseType::where('id', $id)->pluck('type_name')->first();
    }

    public function movementType($id){
        $name = '';
        if($id == 1)
            $name = 'Compound';
        elseif($id == 2)
            $name = 'Isolated';
        elseif($id == 3)
            $name = 'Isometric';

        return $name;
    }

    protected function movementTypeByName($string){
        $id = 0;
        $str = strtolower($string);
        if($str == 'compound')
            $id = 1;
        elseif($str == 'isolated')
            $id = 2;
        elseif($str == 'isometric')
            $id = 3;

        return $id;
    }

    public function movementPattern($id){
        return \App\AbExerciseMovementPattern::where('id', $id)->pluck('pattern_name')->first();
    }

    public function exeImageArray(){
        return $this->exeimages()->orderBy('aei_id','desc')->pluck('aei_image_name')->toArray();
    }

    public function exeImageId(){
        return $this->exeimages()->orderBy('aei_id','desc')->pluck('aei_id')->first();
    }

    public function exerciseEquipment(){
        return $this->belongsTo('App\AbEquipment','equipment');
    }

    public function exerciseAbility(){
        return $this->belongsTo('App\AbAbility','ability');
    }

    public function type(){
        return $this->belongsTo('App\AbExerciseType','exerciseTypeID');
    }

    public function exerciseMovPattern(){
        return $this->belongsTo('App\AbExerciseMovementPattern','movement_pattern');
    } 
}
