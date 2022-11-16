<?php

namespace App\Models\PipelineProcess;

use Illuminate\Database\Eloquent\Model;

class PipelineProcessTask extends Model
{
    protected $table = 'pipeline_process_tasks';
    protected $fillable = [
        'column_id','user_id','assign_by','original_user_id','task_id', 'content','index','due_date','priority','sales_group','total_sales','completed_at',
        'created_at','updated_at'
    ];

    /**
     * Mark task as complete.
     *
     * @return void
     */
    public function markAsCompleted()
    {
        $this->update([
            'completed_at' => now()
        ]);
    }

    /**
     * Mark task as incomplete.
     *
     * @return void
     */
    public function markAsIncomplete()
    {
        $this->update([
            'completed_at' => null
        ]);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function column()
    {
        return $this->belongsTo('App\Models\PipelineProcess\Column','column_id','id');
    }

    public function assignUser()
    {
        return $this->belongsTo('App\Staff','user_id','id');   
    }

    public function clients()
    {
        return $this->belongsTo('App\Clients','content','id');   
    }

    function child(){
        return $this->hasMany('App\Models\PipelineProcess\PipelineProcessTask', 'task_id', 'id');
    }

    function parent(){
        return $this->hasone('App\Models\PipelineProcess\PipelineProcessTask', 'id', 'task_id');
    }
}
