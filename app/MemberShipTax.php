<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberShipTax extends Model{
    use SoftDeletes;
    
    protected $table = 'membership_tax';
    protected $primaryKey = 'id';

    protected $fillable = [
   
    'mtax_label',
    'mtax_rate',
    'mtax_business_id'
    
    ];


   
}