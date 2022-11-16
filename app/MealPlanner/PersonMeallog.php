<?php

namespace App\MealPlanner;

class PersonMealLog
{

    public function Day($day_id)
    {
        if ($day_id == 1) {
            return 'Sunday';
        }
        if ($day_id == 2) {
            return 'Monday';
        }
        if ($day_id == 3) {
            return 'Tuesday';
        }
        if ($day_id == 4) {
            return 'Wednesday';
        }
        if ($day_id == 5) {
            return 'Thursday';
        }
        if ($day_id == 6) {
            return 'Friday';
        }
        if ($day_id == 7) {
            return 'Saturday';
        }

        
    }
}
