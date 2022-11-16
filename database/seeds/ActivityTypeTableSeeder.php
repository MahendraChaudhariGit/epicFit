<?php

use Illuminate\Database\Seeder;
use App\ActivityType;

class ActivityTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['Any','Run','Walk','Bike Ride','Hike','Gym Workout','Swim','Class Workout','Softball','Tennis','Yoga Class','Baseball','Golf','Soccer, Sport','Gymnastics','Indoor Volleyball','Basketball','Football, Competitive'];
        
        foreach($data as $val){
            ActivityType::create(['type'=>$val]);
        }
    }
}
