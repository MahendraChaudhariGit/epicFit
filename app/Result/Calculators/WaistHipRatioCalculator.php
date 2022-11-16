<?php

namespace App\Result\Calculators;

class WaistHipRatioCalculator
{
    use CalculatorsTrait;

    const GENDER = 'male';

    public $gender;

    public $waist;

    public $hip;

    public function calculate()
    {
        $data = [
            'ratio'  => 0,
            'bs'  => '',
            'interpretation'  => ''
        ];

        $data['ratio'] = round(($this->waist / $this->hip) * 100) ;

        if ($this->gender === self::GENDER) {
            $data['bs'] = $this->calculateBodyShapeForMale($data['ratio']);
        } else {
            $data['bs'] = $this->calculateBodyShapeForFemale($data['ratio']);
        }

        $data['interpretation'] = $this->calculateBodyShapeInterpretation($data['bs']);

        return $data;
    }


    public function calculateBodyShapeForMale($ratio)
    {
        if ($ratio >= 95) {
            return 'Apple';
        }

        if ($ratio < 95) {
            return 'Pear';
        }
    }

    public function calculateBodyShapeForFemale($ratio)
    {
        if ($ratio > 80) {
            return 'Apple';
        }

        if ($ratio <= 80) {
            return 'Pear';
        }
    }

    public function calculateBodyShapeInterpretation($shape)
    {
        if ($shape === 'Apple') {
            return 'Increased risk of heart disease and other related conditions';
        }

        if ($shape === 'Pear') {
            return 'Reduced risk of heart disease and other related conditions';
        }
    }
}
