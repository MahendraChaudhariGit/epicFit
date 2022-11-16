<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class InvoiceItems extends Model{
	use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice_items';
    protected $primaryKey = 'inp_id';
    protected $guarded = [];

    public function invoice(){
        return $this->belongsTo('App\Invoice','inp_invoice_id');
    }
	
	public function staff(){
        return $this->belongsTo('App\Staff','inp_staff_id');
    }

}
