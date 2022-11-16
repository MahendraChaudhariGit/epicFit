<?php

namespace App\Result\Calculators;

class FullBodyAnalysisCalculator
{
    use CalculatorsTrait;

    const GENDER = 'male';

    public $gender;

    public $age;

    public $rhra;

    public $waist;

    public $hip;

    public $elbow;

    public $activity;

    public $goal;

    public function metric($weight, $height)
    {
        $bodyMassIndexCalculator = new BodyMassIndexCalculator;

        $waistHipRatioCalculator = new WaistHipRatioCalculator;

        $waistHipRatioCalculator->gender = $this->gender;
        $waistHipRatioCalculator->waist  = $this->waist;
        $waistHipRatioCalculator->hip    = $this->hip;

        $idealWeightCalculator = new IdealWeightCalculator;

        $leanBodyMassCalculator = new LeanBodyMassCalculator;

        $leanBodyMassCalculator->gender = $this->gender;

        $dailyMetabolismCalculator = new DailyMetabolismCalculator;

        $dailyMetabolismCalculator->gender   = $this->gender;
        $dailyMetabolismCalculator->age      = $this->age;
        $dailyMetabolismCalculator->activity = $this->activity;

        $targetHeartRateCalculator = new TargetHeartRateCalculator;

        $data['body_mass_index']   = $bodyMassIndexCalculator->metric($height, $weight);
        $data['waist_hip_ratio']   = $waistHipRatioCalculator->calculate();
        $data['ideal_weight']      = $idealWeightCalculator->metric($this->gender, $height);
        $data['lean_body_mass']    = $leanBodyMassCalculator->metric($weight, $height);
        $data['daily_metabolism']  = $dailyMetabolismCalculator->metric($weight, $height);
        $data['target_heart_rate'] = $targetHeartRateCalculator->calculate($this->goal, $this->age, $this->rhra);

        return $data;
    }

    public function imperial($weight, $height_ft, $height_in)
    {
        $bodyMassIndexCalculator = new BodyMassIndexCalculator;

        $waistHipRatioCalculator = new WaistHipRatioCalculator;

        $waistHipRatioCalculator->gender = $this->gender;
        $waistHipRatioCalculator->waist  = $this->waist;
        $waistHipRatioCalculator->hip    = $this->hip;

        $idealWeightCalculator = new IdealWeightCalculator;

        $leanBodyMassCalculator = new LeanBodyMassCalculator;

        $leanBodyMassCalculator->gender = $this->gender;

        $dailyMetabolismCalculator = new DailyMetabolismCalculator;

        $dailyMetabolismCalculator->gender   = $this->gender;
        $dailyMetabolismCalculator->age      = $this->age;
        $dailyMetabolismCalculator->activity = $this->activity;

        $targetHeartRateCalculator = new TargetHeartRateCalculator;

        $data['body_mass_index']   = $bodyMassIndexCalculator->imperial($height_ft, $height_in, $weight);
        $data['waist_hip_ratio']   = $waistHipRatioCalculator->calculate();
        $data['ideal_weight']      = $idealWeightCalculator->imperial($this->gender, $height_ft, $height_in);
        $data['lean_body_mass']    = $leanBodyMassCalculator->imperial($weight, $height_ft, $height_in);
        $data['daily_metabolism']  = $dailyMetabolismCalculator->imperial($weight, $height_ft, $height_in);
        $data['target_heart_rate'] = $targetHeartRateCalculator->calculate($this->goal, $this->age, $this->rhra);

        return $data;
    }

    public function calculateBodyFrameSizeForMale($height)
    {
        if ($height > 163) {
            if ($this->elbow < 6.4) {
                return 'Small';
            }

            if ($this->elbow >= 6.4 && $this->elbow <= 7.3) {
                return 'Medium';
            }

            if ($this->elbow > 7.3) {
                return 'Large';
            }
        }

        if ($height >= 163 && $height <= 171) {
            if ($this->elbow < 6.7) {
                return 'Small';
            }

            if ($this->elbow >= 6.7 && $this->elbow <= 7.3) {
                return 'Medium';
            }

            if ($this->elbow > 7.3) {
                return 'Large';
            }
        }

        if ($height >= 172 && $height <= 181) {
            if ($this->elbow < 7) {
                return 'Small';
            }

            if ($this->elbow >= 7 && $this->elbow <= 7.6) {
                return 'Medium';
            }

            if ($this->elbow > 7.6) {
                return 'Large';
            }
        }

        if ($height >= 182 && $height <= 191) {
            if ($this->elbow < 7) {
                return 'Small';
            }

            if ($this->elbow >= 7 && $this->elbow <= 7.9) {
                return 'Medium';
            }

            if ($this->elbow > 7.9) {
                return 'Large';
            }
        }

        if ($height >= 192 && $height <= 201) {
            if ($this->elbow < 7.3) {
                return 'Small';
            }

            if ($this->elbow >= 7.3 && $this->elbow <= 8.3) {
                return 'Medium';
            }

            if ($this->elbow > 8.3) {
                return 'Large';
            }
        }
    }

    public function calculateBodyFrameSizeForFemale($height)
    {
        if ($height >= 163 && $height <= 180 && $this->elbow < 6) {
            return 'Small';
        }
    }
}
