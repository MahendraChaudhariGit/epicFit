<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HydrationJournal extends Model
{
	use SoftDeletes;
    const WATER = 1, COFFEE = 2, TEA = 3, JUICE = 4, SODA=5, MILK_ALCOHAL = 6, ALCOHAL=7, SPORTS_DRINKS = 8;

    protected $table = 'hydration_journal';

    protected $guarded = [];

}
