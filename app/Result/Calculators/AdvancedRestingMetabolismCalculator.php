<?php

namespace App\Result\Calculators;

class AdvancedRestingMetabolismCalculator
{
    use CalculatorsTrait;

    const GENDER = 'male';

    public $age;

    public $gender;

    public function metric($weight, $height)
    {
        if ($this->gender === self::GENDER) {
            $data['arm'] = floor(66 + (6.23 * ($weight * 2.2)) + (12.7 * ($height / 2.54)) - (6.8 * $this->age));
        } else {
            $data['arm'] = floor(655 + (4.35 * ($weight * 2.2)) + (4.7 * ($height / 2.54)) - (4.7 * 29));
        }

        return $data;
    }

    public function imperial($weight, $height_ft, $height_in)
    {

        $height = $this->calculateHeight($height_ft, $height_in);

        if ($this->gender === self::GENDER) {
            $data['arm'] = floor(66 + (6.23 * $weight) + (12.7 * $height) - (6.8 * $this->age));
        } else {
            $data['arm'] = floor(655 + (4.35 * $weight) + (4.7 * $height) - (4.7 * $this->age));
        }
//        dd($data['arm']);
        return $data;
    }
}
