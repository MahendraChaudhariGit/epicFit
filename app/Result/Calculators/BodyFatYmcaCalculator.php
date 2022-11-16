<?php

namespace App\Result\Calculators;

class BodyFatYmcaCalculator
{
    const GENDER = 'male';

    public $gender;

    public $waist;

    public function metric($weight)
    {
        $data = [
            'bf'  => 0,
            'fm'  => 0,
            'lm'  => 0,
            'bfc' => ''
        ];

        if ($this->gender === self::GENDER) {
            $data['bf'] = round(((-98.42 + (4.15 * ($this->waist / 2.54)) - (.082 * ($weight * 2.2))) / ($weight * 2.2)) * 100);

            $data['fm'] = round($weight * ($data['bf'] / 100));

            $data['lm'] = $weight - $data['fm'];

            $data['bfc'] = $this->calculateBodyFatCategoryForMale($data['bf']);
        } else {
            $data['bf'] = round(((-76.76 + (4.15 * ($this->waist / 2.54)) - (.082 * ($weight * 2.2))) / ($weight * 2.2)) * 100);

            $data['fm'] = round($weight * ($data['bf'] / 100));

            $data['lm'] = $weight - $data['fm'];

            $data['bfc'] = $this->calculateBodyFatCategoryForFemale($data['bf']);
        }

        return $data;
    }

    public function imperial($weight)
    {
        $data = [
            'bf'  => 0,
            'fm'  => 0,
            'lm'  => 0,
            'bfc' => ''
        ];

        if ($this->gender === self::GENDER) {
            $data['bf'] = round(((-98.42 + (4.15 * $this->waist) - (.082 * $weight)) / $weight) * 100);

            $data['fm'] = round($weight * ($data['bf'] / 100));

            $data['lm'] = $weight - $data['fm'];

            $data['bfc'] = $this->calculateBodyFatCategoryForMale($data['bf']);
        } else {
            $data['bf'] = round(((-76.76 + (4.15 * $this->waist) - (.082 * $weight)) / $weight) * 100);

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
