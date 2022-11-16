<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Session;

class StaffAttendence extends Model{
    use SoftDeletes;
    
	protected $table = 'staff_attendences';
    protected $fillable = [

    'sa_start_time',
    'sa_end_time',
    'sa_staff_id',
    'sa_status',
    'sa_date',
    'se_notes',
    'edited_start_time',
    'edited_end_time',
    'sa_entity_number'

    ];

}


?>