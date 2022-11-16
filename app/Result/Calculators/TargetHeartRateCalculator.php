<?php

namespace App\Result\Calculators;

class TargetHeartRateCalculator
{
    public function calculate($goal, $age, $rhra)
    {
        $base = $this->calculateBaseData($goal, $age, $rhra);

        return [
            'bpml'   => floor($base['thr1']),
            'bpmh'   => floor($base['thr2']),
            'mhr'    => floor($base['mhr']),
            'bptsl'  => floor($base['thr1'] / 6),
            'bptsh'  => floor($base['thr2'] / 6),
            'mhrits' => floor($base['mhr'] / 6)
        ];
    }

    public function calculateBaseData($goal, $age, $rhra)
    {
        $data = [
            'mhr'  => 0,
            'thr1' => 0,
            'thr2' => 0
        ];

        $data['mhr'] = 220 - $age;

        if ($goal === 'get-fit') {
            $data['thr1'] = ($data['mhr'] - $rhra) * .5 + $rhra;

            $data['thr2'] = ($data['mhr'] - $rhra) * .6 + $rhra;
        }

        if ($goal === 'lose-weight') {
            $data['thr1'] = ($data['mhr'] - $rhra) * .6 + $rhra;

            $data['thr2'] = ($data['mhr'] - $rhra) * .7 + $rhra;
        }

        if ($goal === 'increase-endurance') {
            $data['thr1'] = ($data['mhr'] - $rhra) * .7 + $rhra;

            $data['thr2'] = ($data['mhr'] - $rhra) * .8 + $rhra;
        }

        if ($goal === 'excellent-fitness') {
            $data['thr1'] = ($data['mhr'] - $rhra) * .8 + $rhra;

            $data['thr2'] = ($data['mhr'] - $rhra) * .9 + $rhra;
        }

        if ($goal === 'competitive-athletics') {
            $data['thr1'] = ($data['mhr'] - $rhra) * .9 + $rhra;

            $data['thr2'] = ($data['mhr'] - $rhra) + $rhra;
        }

        return $data;
    }
}
