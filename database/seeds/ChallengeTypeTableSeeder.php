<?php

use Illuminate\Database\Seeder;
use App\ChallengeType;

class ChallengeTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['Most Workouts','Most Distance','Most Calories Burned','Most Workout Time'];
        foreach($data as $val){
            ChallengeType::create(['type'=>$val]);
        }
        
    }
}
