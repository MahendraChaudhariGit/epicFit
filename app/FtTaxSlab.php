<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FtTaxSlab extends Model
{
	
	protected $fillable = [
		'tax_preference_id',
		'from_amount',
		'to_amount',
		'tax_percentages'
	];

	public static function finalCalculations($band_top, $band_rate, $amount){
		$income = $amount;
		for($i = count($band_top) ; $i >= 1 ; $i--){
			if($income > $band_top[$i]) {
			$band[] = ($income - $band_top[$i]) * $band_rate[$i+1];
			$income = $band_top[$i];
			}
		}

	$band[] = $income * $band_rate[1];
	return $band;
	}

}