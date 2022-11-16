<?php

namespace App\Models\PipelineProcess;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $fillable = ['id','project_id', 'member_id','created_at','updated_at'];
}
