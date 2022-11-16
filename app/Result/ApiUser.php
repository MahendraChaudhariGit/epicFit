<?php
namespace App\Result;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ApiUser extends Model{
    use SoftDeletes;

    protected $table = 'api_users';
    protected $primaryKey = 'id';
    protected $fillable = ['token']; 
}
