<?php

namespace App\Result\Calculators;

class DailyMetabolismCalculator
{
    use CalculatorsTrait;

    const GENDER = 'male';

    public $age;

    public $gender;

    public $activity;

    public function metric($weight, $height)
    {
        $data = [
            'aam'   => 0,
            'aamph' => 0,
            'arm'   => 0
        ];

        $advancedRestingMetabolismCalculator = new AdvancedRestingMetabolismCalculator;

        $advancedRestingMetabolismCalculator->gender = $this->gender;
        $advancedRestingMetabolismCalculator->age    = $this->age;

        $data['arm'] = $advancedRestingMetabolismCalculator->metric($weight, $height)['arm'];

        $data['aam'] = round($data['arm'] * $this->getActivityLevelFactor());

        $data['aamph'] = round($data['aam'] / 24);

        return $data;
    }

    public function imperial($weight, $height_ft, $height_in)
    {
        $data = [
            'aam'   => 0,
            'aamph' => 0,
            'arm'   => 0
        ];


        $advancedRestingMetabolismCalculator = new AdvancedRestingMetabolismCalculator;

        $advancedRestingMetabolismCalculator->gender = $this->gender;
        $advancedRestingMetabolismCalculator->age    = $this->age;

        $arm = $advancedRestingMetabolismCalculator->imperial($weight, $height_ft, $height_in);

        $data['arm'] = $arm['arm'];

        $data['aam'] = round($arm['arm'] * $this->getActivityLevelFactor());

        $data['aamph'] = round($data['aam'] / 24);

        return $data;
    }

    public function getActivityLevelFactor()
    {
        if ($this->activity === 'sedentary') {
            return 1.2;
        }

        if ($this->activity === 'lightly-active') {
            return 1.375;
        }

        if ($this->activity === 'moderately-active') {
            return 1.55;
        }

        if ($this->activity === 'very-active') {
            return 1.725;
        }

        if ($this->activity === 'extremely-active') {
            return 1.9;
        }
    }
}
