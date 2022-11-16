<?php

namespace App\Models\PipelineProcess;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
        'user_id','task_id', 'content',
        'created_at','updated_at'
    ];
}
