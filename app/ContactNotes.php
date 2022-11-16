<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ContactNotes extends Model{
	use SoftDeletes;

    protected $table = "contact_notes";
	protected $fillable = [
		'client_id',
		'user_id',
		'status',
		'callback',
		'notes',
		'contactResult',
		'callback_time'
	];

  	/*public function getCreatedDateUiAttribute(){
  		if($this->created_at !=NULL)
  			return dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at));
        return '';	
	}	*/

	public function getCreatedDateUiAttribute(){
  		if($this->created_at != NULL)
  			return setLocalToBusinessTimeZone($this->created_at, 'dateTimeString');
        return '';	
	}	
}
