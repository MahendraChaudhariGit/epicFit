<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NutritionalJournal extends Model
{
    protected $table = 'nutritional_journals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id','food_description','activity_lavel','weight', 'weight_loss_gain','nutritional_habits','how_many_time_eat',
        'skip_meals','eat_first_meal','eat_last_meal','water_drink','drink_alcohol','consume_alcohol','type_of_alcohol','bing_drink',
        'drink_tea_coffee','drink_tea_coffee_desc','tea_coffee_time','cup_size','morning_energy_label','afternoon_energy_label',
        'evening_energy_label','eat_calories','how_many_calories','special_diet','which_diet','all_vitamins',
        'use_it','uses_desc','prepare_own_food','prepare_own_meals','prepare_own_meals_desc','eat_outside',
        'improving_area','must_improving_area','eating_speed','full_plate','finish_plate','always_hungry',
        'plate_empty','eat_upset','eat_fast_food','why_not_eat','why_eat','favourite_food','after_full',
        'after_dinner','good_meal','favourite_drinks','cook_for','updated_at','created_at'
    ];
}
