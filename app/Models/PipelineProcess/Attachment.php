<?php

namespace App\Models\PipelineProcess;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'attachments';
    protected $fillable = [
        'comment_id','filename',
        'created_at','updated_at'
    ];
}
