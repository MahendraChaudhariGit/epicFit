<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffEventHistoryLog extends Model{
	protected $table = 'staff_event_history_logs';
	protected $primaryKey = 'sehl_id';

    public function history(){
      return $this->belongsTo('App\StaffEventHistory', 'sehl_seh_id');
    }
}
