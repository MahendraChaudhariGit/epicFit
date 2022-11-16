<?php 
namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Benchmarks extends Model{
	use SoftDeletes;
	
	protected $fillable = [
	'nps_manual_time',
	'nps_day',
	'nps_time_hour',
	'nps_time_min',
	'nps_automatic_time',
	'client_id',
	'stress',
	'sleep',
	'nutrition',
	'hydration',
	'humidity',
	'benchmarkTemperature',
	'waist',
	'hips',
	'height',
	'weight',
	'neck',
	'shoulders',
	'chest',
	'bicep',
	'forearm',
	'bellybutton',
	'thighs',
	'calves',
	'benchmark_type',
	'benchmark_date',
	'pressups',
	'plank',
	'timetrial3k',
	'cardiobpm1',
	'cardiobpm2',
	'cardiobpm3',
	'cardiobpm4',
	'cardiobpm5',
	'cardiobpm6',
	'updated_at'
	];
	
	public function client(){
		return $this->belongsTo('App\Clients');
	}

	static function updateBenchmarks($input, $id){
		return DB::table('benchmarks')->where('id', $id)->update($input);
	}


}
