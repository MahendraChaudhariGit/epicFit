<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class StaffEventBusyRepeat extends Model{
    use SoftDeletes;
    protected $table = 'staff_event_busy_repeat';
    protected $primaryKey = 'sebr_id';
	protected $guarded = [];
}