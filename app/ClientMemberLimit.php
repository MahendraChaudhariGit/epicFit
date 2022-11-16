<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ClientMemberLimit extends Model{
    use SoftDeletes;
    
    protected $table = 'client_membership_limts';
    protected $fillable = [
	    'cme_client_id',
	    'cme_cm_id',
		'cme_classes_weekly',
		'cme_classes_monthly',
		'cme_classes_fortnight',
		'cme_services_weekly',
		'cme_services_monthly',
		'cme_services_fortnight'
    ];
}