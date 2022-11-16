<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $table = 'events';
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'day', 'hour', 'minute', 'item_code', 'half_hour_rate', 'time_zone_offset'];

    /**
     * Get user of an event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
