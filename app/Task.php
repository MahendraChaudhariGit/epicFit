<?php 
namespace App;
use DB;
use Session;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Task extends Model{
    use SoftDeletes;
    
    protected $table = 'tasks';
    protected $fillable = [
    'task_tr_id',
    'task_business_id',
    'task_name',
    'task_due_date',
    'task_category',
    'task_due_time',
    'task_status',
    'completed_by',
    'is_repeating',
    'task_parent_id',
    'deleted_by',
    'task_user_id',
    'task_client_id',
    'task_note'

    ];

    public function scopeOfTasks($query, $date){
            return $query->OfBusiness()->where('task_due_date','>=',$date)->orderBy('task_due_date')->orderBy('task_status');

    }

    public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->where('task_business_id', $bussId);
    }

    static function findTask($taskId, $bussId = 0){
        return Task::OfBusiness($bussId)->find($taskId);
    }

    public function reminders(){
        return $this->hasMany('App\TaskReminder', 'tr_task_id');
    }

    public function repeat(){
        return $this->belongsTo('App\TaskRepeat', 'task_tr_id', 'tr_id');
    }
   

    public function completer(){
        return $this->belongsTo('App\Models\Access\User\User','completed_by');
    }

    public function categoryName(){
        return $this->belongsTo('App\TaskCategory','task_category');
    }
    
    


    protected static function boot(){
            parent::boot();
            static::deleting(function($event){
                
            });
        }

}