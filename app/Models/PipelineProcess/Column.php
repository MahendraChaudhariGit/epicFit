<?php

namespace App\Models\PipelineProcess;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    protected $table = 'columns';
    protected $fillable = [
        'name','index', 'project_id','client_id',
        'created_at','updated_at'
    ];

    public function pipeline_process_tasks(){
        return $this->hasMany('App\Models\PipelineProcess\PipelineProcessTask','column_id','id')->orderBy('index','asc');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\PipelineProcess\Project','project_id','id');
    }
}
