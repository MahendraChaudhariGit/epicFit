<?php

namespace App\Result\Calculators;

class BodyFatNavyCalculator
{
    use CalculatorsTrait;

    const GENDER = 'male';

    public $gender;

    public $waist;

    public $hip;

    public $neck;

    public function metric($weight, $height)
    {
        $data = [
            'bf'  => 0,
            'fm'  => 0,
            'lm'  => 0,
            'bfc' => ''
        ];


        if ($this->gender === self::GENDER) {

            $data['bf'] = round(495 / (1.0324 - .19077 * log10($this->waist - $this->neck) + .15456 * log10($height)) - 450);

            $data['fm'] = round($weight * ($data['bf'] / 100));

            $data['lm'] = $weight - $data['fm'];

            $data['bfc'] = $this->calculateBodyFatCategoryForMale($data['bf']);
            
        } else {
            $data['bf'] = round(495 / (1.29579 - .35004 * log10($this->waist + $this->hip - $this->neck) + .22100 * log10($height)) - 450);

            $data['fm'] = round($weight * ($data['bf'] / 100));

            $data['lm'] = $weight - $data['fm'];

            $data['bfc'] = $this->calculateBodyFatCategoryForFemale($data['bf']);
        }

        return $data;
    }

    public function imperial($weight, $height_ft, $height_in)
    {
        $height = $this->calculateHeight($height_ft, $height_in);

        $data = [
            'bf'  => 0,
            'fm'  => 0,
            'lm'  => 0,
            'bfc' => ''
        ];

        if ($this->gender === self::GENDER) {
            $data['bf'] = round(495 / (1.0324 - .19077 * log10(($this->waist * 2.54) - ($this->neck * 2.54)) + .15456 * log10($height * 2.54)) - 450);

            $data['fm'] = round($weight * ($data['bf'] / 100));

            $data['lm'] = $weight - $data['fm'];

            $data['bfc'] = $this->calculateBodyFatCategoryForMale($data['bf']);
        } else {
            $data['bf'] = round(495 / (1.29579 - .35004 * log10(($this->waist * 2.54) + ($this->hip * 2.54) - ($this->neck * 2.54)) + .22100 * log10($height * 2.54)) - 450);

            $data['fm'] = round($weight * ($data['bf'] / 100));

            $data['lm'] = $weight - $data['fm'];

            $data['bfc'] = $this->calculateBodyFatCategoryForFemale($data['bf']);
        }

        return $data;
    }

    public function calculateBodyFatCategoryForMale($body_fat)
    {
        if ($body_fat >= 26) {
            return 'Obese';
        }

        if ($body_fat >= 18 && $body_fat <= 25) {
            return 'Acceptable';
        }

        if ($body_fat >= 14 && $body_fat <= 17) {
            return 'Fit';
        }

        if ($body_fat >= 6 && $body_fat <= 13) {
            return 'Athletic';
        }

        if ($body_fat >= 2 && $body_fat <= 5) {
            return 'Essential Fat';
        }

        if ($body_fat <= 1) {
            return 'Dangerously Low';
        }
    }

    public function calculateBodyFatCategoryForFemale($body_fat)
    {
        if ($body_fat >= 32) {
            return 'Obese';
        }

        if ($body_fat >= 25 && $body_fat <= 31) {
            return 'Acceptable';
        }

        if ($body_fat >= 21 && $body_fat <= 24) {
            return 'Fit';
        }

        if ($body_fat >= 14 && $body_fat <= 20) {
            return 'Athletic';
        }

        if ($body_fat >= 10 && $body_fat <= 13) {
            return 'Essential Fat';
        }

        if ($body_fat <= 9) {
            return 'Dangerously Low';
        }
    }
}
