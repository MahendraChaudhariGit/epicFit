<?php 
namespace App;
use DB;
use Session;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TaskReminder extends Model{
    use SoftDeletes;
    
    protected $table = 'task_reminders';
    protected $fillable = [
    
    'tr_is_set',
    'tr_hours',
    'tr_datetime',
    'tr_task_id',

    ];
   
}