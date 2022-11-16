<?php

namespace App\Result\Calculators;

class CalorieBreakdownCalculator
{
    const  GENDER = 'male';

    public function calculate($gender, $age, $calorie)
    {
        $data = [
            'fatl'          => round($calorie * .15),
            'fath'          => round($calorie * .25),
            'proteinl'      => round($calorie * .15),
            'proteinh'      => round($calorie * .25),
            'carbohydratel' => round($calorie * .5),
            'carbohydrateh' => round($calorie * .7),
            'sugar'         => round($calorie * .25),
            'fiber'         => ''
        ];

        if ($gender === self::GENDER) {
            if ($age < 50) {
                $data['fiber'] = 38;
            } else {
                $data['fiber'] = 30;
            }
        } else {
            if ($age < 50) {
                $data['fiber'] = 25;
            } else {
                $data['fiber'] = 21;
            }
        }

        return $data;
    }
}
