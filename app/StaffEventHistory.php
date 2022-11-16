<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffEventHistory extends Model{
	use SoftDeletes;
	protected $table = 'staff_event_histories';
	protected $primaryKey = 'seh_id';
	protected $guarded = [];

    public function historyable(){
      return $this->morphTo();
    }

    public function logs(){
      return $this->hasMany('App\StaffEventHistoryLog', 'sehl_seh_id');
    }
}
