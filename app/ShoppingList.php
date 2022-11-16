<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingList extends Model{
    use SoftDeletes;
    protected $guarded = [];
    
    protected $table = 'shopping_list';
   


   
}