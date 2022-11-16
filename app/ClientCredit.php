<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientCredit extends Model{
	use SoftDeletes;
	
	protected $table = 'client_credits';
	protected $primaryKey = 'cc_id';
    protected $fillable = ['cc_amount', 'cc_expiry', 'cc_reason'];


    public function client(){
        return $this->belongsTo('App\Clients', 'cc_client_id');
    }

}
