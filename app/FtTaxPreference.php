<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FtTaxPreference extends Model
{ 
	  protected $primaryKey = 'id';
	protected $fillable = [
		'tax_category',
		'business_id',
		'tax_type',
		'tax_amount',
		'tax_code',
		'tax_name',
		'country',
		'financial_time_frame',
	];

	public function slabs()
	{
		return $this->hasMany('App\FtTaxSlab','tax_preference_id','id');
	}
}
