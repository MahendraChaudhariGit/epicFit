<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SleepQuestionnaire extends Model
{
    protected $table = 'sleep_questionnaires';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id','first_name','last_name','contact_number','contact_time', 'email','gender','age','date',
        'ethnicity','marital_status','enough_sleep','quality_sleep','sleep_affect','wakeup_early','planning_at_night','have_an_injury',
        'have_a_partner','have_a_child','have_a_pets','heartburn_or_indigestion','work_affect','work_stress',
        'wakeup_in_night','wakeup_with_active','getup_score','goto_bed_score','specific_time_getup_score','easy_to_getup_score',
        'first_half_hour_score','first_half_hour_hungry_score','first_half_hour_tired_score','no_commitment_goto_bed_score','engage_in_physical_exercise_score','feel_tired_in_day_score',
        'peak_performance_score','got_into_bed_tired_score','goto_several_hour_into_bed_score','one_night_awake_score','two_hour_hard_work_score','physical_exercise_score',
        'school_hours_score','feeling_best_score','types_of_people_score','total_score','updated_at','created_at'
    ];
}
