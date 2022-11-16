<?php 
namespace App;
use DB;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffAttendee extends Model{
    use SoftDeletes;
    
    protected $table = 'staff_attendees';
    protected $fillable = [
   
    'sa_staff_id',
    'sa_type',
    'sa_per_session_attendees',
    'sa_per_session_attendeeto',
    'sa_per_session_price',
    'sa_attendee_order',
    'per_session_tier',
    'per_session_tierto',
    'per_session_tierprice'
    ];


   
}