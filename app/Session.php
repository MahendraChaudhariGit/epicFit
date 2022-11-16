<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {

	//
	protected $fillable = [
		'location_id',
		'title',
		'session_day',
		'start_time',
		'end_time',
		'session_day',
		'attendee_limit'
	];
	public function bookings()
	{
		return $this->hasMany('App\Bookings');
	}

}
