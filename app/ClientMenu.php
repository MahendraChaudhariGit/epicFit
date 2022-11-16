<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientMenu extends Model
{
	protected $table = 'client_menues';
	public $timestamps = false;
    protected $fillable = [
		'client_id',
		'menues',
	];

	public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
}
