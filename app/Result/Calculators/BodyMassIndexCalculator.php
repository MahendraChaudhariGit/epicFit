<?php

namespace App\Result\Calculators;

class BodyMassIndexCalculator
{
    use CalculatorsTrait;

    const METRIC_UNIT = 'kg';

    const IMPERIAL_UNIT = 'lbs';

    public function metric($height, $weight)
    {
        $bmi = $weight / ($height * $height) * 10000;

        return $this->calculateClassificationAndWeightRange($bmi, $weight, self::METRIC_UNIT);
    }

    public function imperial($height_ft, $height_in, $weight)
    {
        $height = $this->calculateHeight($height_ft, $height_in);

        $bmi = $weight / ($height * $height) * 703;

        return $this->calculateClassificationAndWeightRange($bmi, $weight, self::IMPERIAL_UNIT);
    }

    public function calculateClassificationAndWeightRange($bmi, $weight, $unit)
    {
        $data = [
            'bmi'            => $this->decimal($bmi),
            'classification' => '',
            'weight_range'   => ''
        ];

        if ($bmi > 40) {
            $data['classification'] = 'Extremely Obese';

            $data['weight_range'] = floor($weight / $bmi * 40)  . ' ' . $unit . ' or more';
        } elseif ($bmi > 30 && $bmi <= 40) {
            $data['classification'] = 'Obese';

            $data['weight_range'] = floor($weight / $bmi * 30) . ' to ' . floor($weight / $bmi * 40) . ' ' . $unit;
        } elseif ($bmi > 25 && $bmi <= 30) {
            $data['classification'] = 'Overweight';

            $data['weight_range'] = floor($weight / $bmi * 25) . ' to ' . floor($weight / $bmi * 30) . ' ' . $unit;
        } elseif ($bmi > 18.5 && $bmi <= 25) {
            $data['classification'] = 'Normal';

            $data['weight_range'] = floor($weight / $bmi * 18.5) . ' to ' . floor($weight / $bmi * 25) . ' ' . $unit;
        } elseif ($bmi <= 18.5) {
            $data['classification'] = 'Underweight';

            $data['weight_range'] = floor($weight / $bmi * 18.5) . ' ' . $unit . ' or less';
        }

        return $data;
    }
}
