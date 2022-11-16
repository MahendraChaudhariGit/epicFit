<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Ldc extends Model{
	use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ldc_sessions';
    protected $primaryKey = 'ldc_id';
    protected $guarded =[];

   
}
