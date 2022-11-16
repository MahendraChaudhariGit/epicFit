<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberShipAddTax extends Model{
    use SoftDeletes;
    
    protected $table = 'membership_addtax';
    protected $fillable = [
   
    'mat_id',
    'mat_member_id',
    'mat_tax_id',
    'mat_tax_order'

    
    ];


   
}