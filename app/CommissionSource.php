<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionSource extends Model{
    use SoftDeletes;
    
    protected $table = 'commission_source';
    protected $fillable = [
   
    'cr_value',
    'cr_businesses_id',
    
    ];


   
}