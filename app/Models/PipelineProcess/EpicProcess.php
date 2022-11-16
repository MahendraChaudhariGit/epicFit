<?php

namespace App\Models\PipelineProcess;

use Illuminate\Database\Eloquent\Model;

class EpicProcess extends Model
{
    protected $table = 'epic_process';
    protected $fillable = [
        'id','column_id','pipeline_process_task_id', 'sales_group','total_sales',
        'created_at','updated_at'
    ];

    public function pipeline_process_tasks(){
        return $this->hasMany('App\Models\PipelineProcess\PipelineProcessTask','column_id','id')->orderBy('index','asc');
    }

    public function task(){
        return $this->belongsTo('App\Models\PipelineProcess\PipelineProcessTask','pipeline_process_task_id','id');
    }

}
