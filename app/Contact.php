<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Session;

class Contact extends Model{
    use SoftDeletes;
    protected $table = 'contacts';
    protected $fillable = ['business_id', 'type', 'is_epic_trainer', 'company_name', 'service', 'service_offered', 'location', 'contact_name', 'preferred_name', 'notes', 'website', 'facebook', 'email', 'phone', 'address_line_one', 'address_line_two', 'city', 'country', 'state', 'postal_code', ''];

    /*
    **start: ACCESSOR
    */
        public function getNameAttribute(){
            if($this->preferred_name == 'Contact Name')
                return $this->contact_name;
            if($this->preferred_name == 'Company Name')
                return $this->company_name;
            return '';
        }
    /*
    **end: ACCESSOR
    */


    /*
    **start: SCOPES
    */
        public function scopeOfBusiness($query, $bussId = 0){
            if(!$bussId)
                $bussId = Session::get('businessId');
            return $query->where('business_id', $bussId);
        }
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
        public function business(){
            return $this->belongsTo('App\Business');
        }
    /*
    **end: RELATIONS
    */


    /*
    **start: FUNCTIONS
    */
        static function getContactTypes($businessId = 0){
            if(!$businessId)
                return DB::table('contact_types')->where('ct_business_id', 0)->pluck('ct_value', 'ct_id')->toArray();
            else
                return DB::table('contact_types')->whereIn('ct_business_id', [0, $businessId])->pluck('ct_value', 'ct_id')->toArray();
        }

        static function findContact($contactId, $bussId = 0){
            return Contact::OfBusiness($bussId)->find($contactId);
        }

        static function findOrFailContact($contactId, $bussId = 0){
            return Contact::OfBusiness($bussId)->findOrFail($contactId);
        }
    /*
    **end: FUNCTIONS
    */    
}