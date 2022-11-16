<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberShipGroup extends Model{
    use SoftDeletes;
    
    protected $table = 'member_group';
    protected $fillable = [
   
    'mg_group',
    'mg_businesses_id',
    
    ];


   
}