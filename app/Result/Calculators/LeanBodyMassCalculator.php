<?php

namespace App\Result\Calculators;

class LeanBodyMassCalculator
{
    use CalculatorsTrait;

    const  GENDER = 'male';

    public $gender;

    public function metric($weight, $height)
    {
        $data = [
            'lm'  => 0,
            'lmp' => 0,
            'fm'  => 0,
            'fmp' => 0
        ];

        if ($this->gender === self::GENDER) {
            $data['lm'] = round((1.1 * $weight) - (128 * (($weight * $weight) / ($height * $height))));

            $data['lmp'] = round(($data['lm'] / $weight) * 100);

            $data['fm'] = $weight - $data['lm'];

            $data['fmp'] = 100 - $data['lmp'];
        } else {
            $data['lm'] = round((1.07 * $weight) - (148 * ($weight * $weight) / ($height * $height)));

            $data['lmp'] = round(($data['lm'] / $weight) * 100);

            $data['fm'] = $weight - $data['lm'];

            $data['fmp'] = 100 - $data['lmp'];
        }

        return $data;
    }

    public function imperial($weight, $height_ft, $height_in)
    {
        $height = $this->calculateHeight($height_ft, $height_in);

        $data = [
            'lm'  => 0,
            'lmp' => 0,
            'fm'  => 0,
            'fmp' => 0
        ];

        if ($this->gender === self::GENDER) {
            $data['lm'] = round(((1.1 * ($weight / 2.2)) - (128 * pow(($weight / 2.2), 2) / pow(($height * 2.54),  2))) * 2.2);

            $data['lmp'] = round(($data['lm'] / $weight) * 100);

            $data['fm'] = $weight - $data['lm'];

            $data['fmp'] = 100 - $data['lmp'];
        } else {
            $data['lm'] = round(((1.07 * ($weight / 2.2)) - (148 * pow(($weight / 2.2), 2) / pow(($height * 2.54),2))) * 2.2);

            $data['lmp'] = round(($data['lm'] / $weight) * 100);

            $data['fm'] = $weight - $data['lm'];

            $data['fmp'] = 100 - $data['lmp'];
        }

        return $data;
    }
}
