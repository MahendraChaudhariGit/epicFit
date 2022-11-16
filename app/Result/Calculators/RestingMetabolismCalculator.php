<?php

namespace App\Result\Calculators;

class RestingMetabolismCalculator
{
    use CalculatorsTrait;

    const UNIT_TYPE = 'percent';

    public function metric($weight, $mass, $unit_type)
    {
        $data = [
            'lm'  => 0,
            'rm'  => 0,
            'lmp' => 0,
            'fm'  => 0,
            'fmp' => 0
        ];

        if ($unit_type === self::UNIT_TYPE) {
            $data['lm'] = $this->decimal($weight * ($mass / 100));

            $data['rm'] = round(370 + (21.6 * $data['lm']));

            $data['lmp'] = $mass;

            $data['fm'] = $this->decimal($weight - $data['lm']);

            $data['fmp'] = 100 - $mass;
        } else {
            $data['lm'] = $mass;

            $data['rm'] = round(370 + (21.6 * $data['lm']));

            $data['lmp'] = $this->decimal(($mass / $weight) * 100);

            $data['fm'] = $this->decimal($weight - $data['lm']);

            $data['fmp'] = $this->decimal(100 - $data['lmp']);
        }

        return $data;
    }

    public function imperial($weight, $mass, $unit_type)
    {
        $data = [
            'lm'  => 0,
            'rm'  => 0,
            'lmp' => 0,
            'fm'  => 0,
            'fmp' => 0
        ];

        if ($unit_type === self::UNIT_TYPE) {
            $data['lm'] = round($weight * ($mass / 100));

            $data['rm'] = round(370 + (21.6 * ($data['lm'] / 2.2)));

            $data['lmp'] = $mass;

            $data['fm'] = round($weight - $data['lm']);

            $data['fmp'] = 100 - $mass;
        } else {
            $data['lm'] = $mass;

            $data['rm'] = round(370 + (21.6 * ($data['lm'] / 2.2)));

            $data['lmp'] = $this->decimal(($mass / $weight) * 100);

            $data['fm'] = $this->decimal($weight - $data['lm']);

            $data['fmp'] = $this->decimal(100 - $data['lmp']);
        }

        return $data;
    }
}
