<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SessionRole extends Model{
    use SoftDeletes;
    
    protected $table = 'session_role';
    protected $fillable = [
   
    'sr_value',
    'sr_businesses_id',
    
    ];


   
}