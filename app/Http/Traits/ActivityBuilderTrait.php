<?php
namespace App\Http\Traits;
use Session;
use Carbon\Carbon;
use App\AbExerciseMovementPattern;
use App\AbWorkoutExercise;
use App\AbEquipment;
use App\AbAbility;
use App\AbMuscleGroup;
use App\AbExerciseType;
use App\AbWorkout;


trait ActivityBuilderTrait{
    /**
     * Return exercise option 
     * @param void
     * @return data 
    **/
    protected function getExercisesOptions(){
        $data = [];
        $equipments = AbEquipment::select('eq_name','id')->get();
        $equipment= [];
        foreach ($equipments as $value) {
            $equipment[$value->id] = $value->eq_name;
        }
        $data['equipments'] = $equipment;

    

        $abilitys = AbAbility::select('name','id')->get();
        $ability= [''=>' -- Select -- '];
        foreach ($abilitys as $value) {
            $ability[$value->id] = $value->name;
        }
        $data['abilitys'] = $ability;

        $movement_patterns = AbExerciseMovementPattern::select('id','pattern_name')->get();
        $movement_pattern= [];
        foreach ($movement_patterns as $value) {
            $movement_pattern[$value->id] = $value->pattern_name;
        }
        $data['movepattern'] = $movement_pattern;

        $bodyparts = AbMuscleGroup::select('id','name')->get();
        $bodypart= [''=>' -- Select -- '];
        foreach ($bodyparts as $value) {
            $bodypart[$value->id] = $value->name;
        }
        $data['bodyparts'] = $bodypart;

        $exercise_types = AbExerciseType::select('id','type_name')->get();
        $exercise_type= [''=>' -- Select -- '];
        foreach ($exercise_types as $value) {
            $exercise_type[$value->id] = $value->type_name;
        }
        $data['exetype'] = $exercise_type;

        return $data;
    }

    /**
     * Store workout for exercise
     * @param input data, exercise id
     * @return boolean(true/false) 
    **/
    protected function storeExercisesWorkouts($id, $inputData){
        if($id){
            $businessId = Session::get('businessId');
            $insertedData = [];
            $timestamp = Carbon::now();
            if(array_key_exists('warm_up', $inputData)){
                $insertedData[] = array('business_id'=>$businessId,'workout_id'=>$inputData['warm_up'], 'exercise_id'=>$id, 'weekIndex'=>1, 'dayIndex'=>1, 'sets'=>$inputData['wuSets'], 'repetition'=>$inputData['wuReps'], 'resistance'=>$inputData['wuResist'], 'restSeconds'=>$inputData['wuRest'], 'estimatedTime'=>$inputData['wuDur'], 'tempoDesc'=>$inputData['wuTempo'], 'created_at'=>$timestamp, 'updated_at'=>$timestamp);
            }
            if(array_key_exists('cardio', $inputData)){
                $insertedData[] = array('business_id'=>$businessId,'workout_id'=>$inputData['cardio'], 'exercise_id'=>$id, 'weekIndex'=>1, 'dayIndex'=>1, 'sets'=>$inputData['cardioSets'], 'repetition'=>$inputData['cardioReps'], 'resistance'=>$inputData['cardioResist'], 'restSeconds'=>$inputData['cardioRest'], 'estimatedTime'=>$inputData['cardioDur'], 'tempoDesc'=>$inputData['cardioTempo'], 'created_at'=>$timestamp, 'updated_at'=>$timestamp);
            }
            if(array_key_exists('exeRt', $inputData)){
                $insertedData[] = array('business_id'=>$businessId,'workout_id'=>$inputData['exeRt'], 'exercise_id'=>$id, 'weekIndex'=>1, 'dayIndex'=>1, 'sets'=>$inputData['exeRtSets'], 'repetition'=>$inputData['exeRtReps'], 'resistance'=>$inputData['exeRtResist'], 'restSeconds'=>$inputData['exeRtRest'], 'estimatedTime'=>$inputData['exeRtDur'], 'tempoDesc'=>$inputData['exeRtTempo'], 'created_at'=>$timestamp, 'updated_at'=>$timestamp);
            }
            if(array_key_exists('skill', $inputData)){
                $insertedData[] = array('business_id'=>$businessId,'workout_id'=>$inputData['skill'], 'exercise_id'=>$id, 'weekIndex'=>1, 'dayIndex'=>1, 'sets'=>$inputData['skillSets'], 'repetition'=>$inputData['skillReps'], 'resistance'=>$inputData['skillResist'], 'restSeconds'=>$inputData['skillRest'], 'estimatedTime'=>$inputData['skillDur'], 'tempoDesc'=>$inputData['skillTempo'], 'created_at'=>$timestamp, 'updated_at'=>$timestamp);
            }
            if(array_key_exists('core', $inputData)){
                $insertedData[] = array('business_id'=>$businessId,'workout_id'=>$inputData['core'], 'exercise_id'=>$id, 'weekIndex'=>1, 'dayIndex'=>1, 'sets'=>$inputData['coreSets'], 'repetition'=>$inputData['coreReps'], 'resistance'=>$inputData['coreResist'], 'restSeconds'=>$inputData['coreRest'], 'estimatedTime'=>$inputData['coreDur'], 'tempoDesc'=>$inputData['coreTempo'], 'created_at'=>$timestamp, 'updated_at'=>$timestamp);
            }
            if(array_key_exists('cool_down', $inputData)){
                $insertedData[] = array('business_id'=>$businessId,'workout_id'=>$inputData['cool_down'], 'exercise_id'=>$id, 'weekIndex'=>1, 'dayIndex'=>1, 'sets'=>$inputData['cdSets'], 'repetition'=>$inputData['cdReps'], 'resistance'=>$inputData['cdResist'], 'restSeconds'=>$inputData['cdRest'], 'estimatedTime'=>$inputData['cdDur'], 'tempoDesc'=>$inputData['cdTempo'], 'created_at'=>$timestamp, 'updated_at'=>$timestamp);
            }
            if(array_key_exists('stretch', $inputData)){
                $insertedData[] = array('business_id'=>$businessId,'workout_id'=>$inputData['stretch'], 'exercise_id'=>$id, 'weekIndex'=>1, 'dayIndex'=>1, 'sets'=>$inputData['rrsSets'], 'repetition'=>$inputData['rrsReps'], 'resistance'=>$inputData['rrsResist'], 'restSeconds'=>$inputData['rrsRest'], 'estimatedTime'=>$inputData['rrsDur'], 'tempoDesc'=>$inputData['rrsTempo'], 'created_at'=>$timestamp, 'updated_at'=>$timestamp);
            }
            if(count($insertedData));{
                if(AbWorkoutExercise::insert($insertedData))
                    return true;
            }
        }
        return false;
    }


    /** 
     * Store all workout with exercise default (for link and search exercise according to workout)
     * @param exercise id
     * @return boolean
    **/
    protected function workoutWithExercise($id){
        $workouts = AbWorkout::select('id','name')->get();
        $businessId = Session::get('businessId');
        $insertedData = array();
        if(count($workouts)){
            $timestamp = Carbon::now();
            foreach ($workouts as $workout) {
                $insertedData[] = array('business_id'=>$businessId,'workout_id'=>$workout->id, 'exercise_id'=>$id, 'created_at'=>$timestamp, 'updated_at'=>$timestamp);
            }
        } 
        if(count($insertedData));{
            if(AbWorkoutExercise::insert($insertedData))
                return true;
        }
        return false;
    }
}