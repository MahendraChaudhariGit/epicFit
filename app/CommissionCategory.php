<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionCategory extends Model{
    use SoftDeletes;
    
    protected $table = 'commission_category';
    protected $fillable = [
   
    'cc_value',
    'cc_businesses_id',
    
    ];


   
}