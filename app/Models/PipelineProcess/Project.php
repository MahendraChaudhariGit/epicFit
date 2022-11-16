<?php

namespace App\Models\PipelineProcess;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    const SLUG_EPIC_SALES_PROCESS = "epic-sales-process";
    protected $table = 'projects';
    protected $fillable = [
        'name','description', 'start_date', 'end_date','user_id', 
        'color', 'visibility', 'slug','favorite','archive','deleted_at','completed_at',
        'created_at','updated_at'
    ];

    /**
     * The project has many columns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function columns(){
        return $this->hasMany('App\Models\PipelineProcess\Column','project_id','id');
    }

    public function projectMember(){
        return $this->hasMany('App\Models\PipelineProcess\ProjectMember','project_id','id');
    }

    /**
     * Mark the project as completed.
     *
     * @return void
     */
    public function markAsCompleted()
    {
        $this->fill([
            'completed_at' => now(),
        ])->save();
    }

    /**
     * Mark the project as incomplete.
     *
     * @return void
     */
    public function markAsIncomplete()
    {
        $this->fill([
            'completed_at' => null,
        ])->save();
    }
}
