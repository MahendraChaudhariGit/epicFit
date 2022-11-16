<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MpServingSize extends Model{
	use SoftDeletes;
    protected $table = 'mpn_serving_size';
    protected $primaryKey = 'id';
    protected $fillable = ['name','size','tags']; 

    /**
     * Relation
     */
}
	 	 	 	 	 	 