<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use DB;
use Carbon\Carbon;
use App\ServiceResources;

class Clas extends Model{
    use SoftDeletes;

	protected $table = 'classes';
	protected $primaryKey = 'cl_id';
    protected $guarded = [];

    public function business(){
        return $this->belongsTo('App\Business', 'cl_business_id');
    }

    public function staffs(){
        return $this->belongsToMany('App\Staff', 'class_staffs', 'cst_cl_id', 'cst_staff_id');
    }

    static function pivotStaffsTrashedOnly($classId){
        return DB::table('class_staffs')->where('cst_cl_id', $classId)->whereNotNull('deleted_at')->select('cst_staff_id')->get();
    }

    public function location(){
        return $this->belongsTo('App\Location', 'cl_location_id');
    }
    
    public function cat(){
        return $this->belongsTo('App\ClassCat', 'cl_clcat_id');
    }

    public function areas(){
        return $this->belongsToMany('App\LocationArea', 'area_classes', 'ac_cl_id', 'ac_la_id');
    }

    static function pivotAreasTrashedOnly($classId){
        return DB::table('area_classes')->where('ac_cl_id', $classId)->whereNotNull('deleted_at')->select('ac_la_id')->get();
    }

    public function eventClasses(){
        return $this->hasMany('App\StaffEventClass', 'sec_class_id');
    }

    public function futureEventClasses(){
        $now = new Carbon();
        return $this->eventClasses()->where('sec_start_datetime', '>=', $now->toDateTimeString());
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($clas){
            DB::table('area_classes')->where('ac_cl_id', $clas->cl_id)->update(array('deleted_at' => createTimestamp()));
            DB::table('class_staffs')->where('cst_cl_id', $clas->cl_id)->update(array('deleted_at' => createTimestamp()));
            ServiceResources::/*where('sr_entity_id',$clas->id)->where('sr_business_id',Session::get('businessId'))->where('sr_entity_type','App\Clas')*/OfClas($clas->cl_id)->delete();

            foreach($clas->futureEventClasses as $eventClass)
                $eventClass->delete();
            
            // Delete invoice
            $invoiceIds = \App\InvoiceItems::select('inp_invoice_id')->where('inp_product_id', $clas->cl_id)->where('inp_type', 'class')->get()->toArray();

            $invoices = \App\Invoice::whereIn('inv_id',$invoiceIds)->get();
            if($invoices->count()){
                foreach ($invoices as $invoice) {
                    $invoice->delete();
                }
            } 
        });
        static::deleted(function(){
            if(!Clas::OfBusiness()->exists())
                Session::forget('ifBussHasClasses');
        });
    }

    public function resources(){
        return $this->morphMany('App\ServiceResources', 'resorcesable', 'sr_entity_type', 'sr_entity_id');
    }

    public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->where('cl_business_id', $bussId);
    }

    static function findClass($classId, $bussId = 0){
        return Clas::OfBusiness($bussId)->find($classId);
    }

    static function findOrFailClass($classId, $bussId = 0){
        return Clas::OfBusiness($bussId)->findOrFail($classId);
    }
}
