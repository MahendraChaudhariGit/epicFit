<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MpShoppingItem extends Model{
	use SoftDeletes;

    protected $table = 'mp_shoppingitem';
    protected $primaryKey = 'shopping_item_id';
    protected $fillable = ['created_date','name','shopping_category','shopping_id'];
}