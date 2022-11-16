<?php 
namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FtPartnership extends Model{

	protected $table = 'ft_partnership';
	
	protected $fillable = [
							'business_id',
							'partnership_expenses',
							'profit_percentage',
							'invested_amount',
							'excl_gst',
							'gst_paid',
						];

	
} 
