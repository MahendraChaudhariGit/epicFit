<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskCategory extends Model{
    use SoftDeletes;
    
    protected $table = 'task_categories';
    protected $fillable = [
 
    't_cat_business_id',
    't_cat_name',
    't_cat_user_id',
    'deleted_by',
    
    ];


    public function tasks(){
        return $this->hasMany('App\Task', 'task_category');
    }

    
    protected static function boot(){
        parent::boot();
        static::deleting(function($category){
            foreach($category->tasks as $task)
                $task->delete(); 
        });    
    }
   
}