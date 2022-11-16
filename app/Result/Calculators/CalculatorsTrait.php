<?php

namespace App\Result\Calculators;

trait CalculatorsTrait
{
    public function calculateHeight($height_ft, $height_in)
    {
        return  $height_ft * 12 + $height_in;
    }

    public function decimal($number, $places = 1)
    {
        return (float)number_format($number, $places, '.', '');
    }

    public function calculateHeightIn($height,$height_ft)
    {
        //return  ($height - $height_ft)/12;
        return  $height-($height_ft*12);
    }

}
