<?php

namespace App\Result\Calculators;

class IdealWeightCalculator
{
    use CalculatorsTrait;

    const  GENDER = 'male';

    public function metric($gender, $height)
    {
        if ($gender === self::GENDER) {
            $data['iw'] = round(50 + 2.3 * (($height / 2.54) - 60));
        } else {
            $data['iw'] = round(49 + 1.7 * (($height / 2.54) - 60));
        }

        return $data;
    }

    public function imperial($gender, $height_ft, $height_in)
    {
        $height = $this->calculateHeight($height_ft, $height_in);

        if ($gender === self::GENDER) {
            $data['iw'] = round((50 + 2.3 * ($height - 60)) * 2.2);
        } else {
            $data['iw'] = round((49 + 1.7 * ($height - 60)) * 2.2);
        }

        return $data;
    }
}
