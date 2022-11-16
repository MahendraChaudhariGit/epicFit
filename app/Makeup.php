<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Auth;

class Makeup extends Model{
	use SoftDeletes;

	protected $table = 'makeup';
    protected $primaryKey = 'makeup_id';

    protected $fillable = ['makeup_client_id','makeup_session_count','makeup_amount','makeup_extra','makeup_user_id','makeup_user_name'];

    public function notes(){
        return $this->belongsTo('App\ClientNote','makeup_notes_id','cn_id');
    }

    public function getPurposeAttribute(){
    	$purpose = $this->makeup_purpose;
    	if($purpose == 'class' || $purpose == 'service' || $purpose == 'manual')
    		return ucfirst($purpose);
    	if($purpose == 'memb_ship_adj')
    		return 'Membership adjustment';
    	if($purpose == 'invoice_amount')
    		return 'Invoice amount';
    	return $purpose;
    }

    public function getEventStartTimeCarbonAttribute(){
        return setLocalToBusinessTimeZone(new Carbon($this->sess_time));
    }
    
    public function getUserInformationAttribute(){
        $user = Auth::user();
        $name = $user->name.' '.$user->last_name;
        return array('id'=>$user->id, 'name'=>ucfirst($name));
    }

}