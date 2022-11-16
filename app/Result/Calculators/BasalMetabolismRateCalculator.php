<?php

namespace App\Result\Calculators;

class BasalMetabolismRateCalculator
{
    use CalculatorsTrait;

    const EQUATION = 'mior';

    const  GENDER = 'male';

    public $age;

    public $gender;

    public $equation;

    public function metric($height, $weight)
    {

        if ($this->equation === self::EQUATION) {
            $base_brm = round((10 * $weight) + (6.25 * $height) - (5 * $this->age));

            $data['brm'] = $this->gender === self::GENDER ? $base_brm + 5 : $base_brm - 161;
        } else {
            if ($this->gender === self::GENDER) {
                $data['brm'] = round(66.47 + (13.75 * $weight) + (5.003 * $height) - (6.755 * $this->age));
            } else {
                $data['brm'] = round(655.1 + (9.563 * $weight) + (1.85 * $height) - (4.676 * $this->age));
            }
        }

        return $data;
    }

    public function imperial($height_ft, $height_in, $weight)
    {
        $height = $this->calculateHeight($height_ft, $height_in);

        if ($this->equation === self::EQUATION) {
            $base_brm = round((4.536 * $weight) + (15.88 * $height) - (5 * $this->age));

            $data['brm'] = $this->gender === self::GENDER ? $base_brm + 5 : $base_brm - 161;
        } else {
            if ($this->gender === self::GENDER) {
                $data['brm'] = round(66.47 + (6.24 * $weight) + (12.7 * $height) - (6.755 * $this->age));
            } else {
                $data['brm'] = round(655.1 + (4.35 * $weight) + (4.7 * $height) - (4.7 * $this->age));
            }
        }

        return $data;
    }
}
