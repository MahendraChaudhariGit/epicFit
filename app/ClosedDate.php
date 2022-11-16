<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Session;

class ClosedDate extends Model{
    use SoftDeletes;
    protected $table = 'closed_dates';
    protected $primaryKey = 'cd_id';
    protected $fillable = ['cd_start_date', 'cd_end_date', 'cd_description','cd_business_id'];

    public function getStartDateAttribute(){
        if($this->cd_start_date != null)
            return dbDateToDateStringg(Carbon::createFromFormat('Y-m-d', $this->cd_start_date));
        return '';
    }

    public function getEndDateAttribute(){
        if($this->cd_end_date != null)
            return dbDateToDateStringg(Carbon::createFromFormat('Y-m-d', $this->cd_end_date));
        return '';
    }


    /*
    **start: SCOPES
    */
        public function scopeOfBusiness($query){
            return $query->where('cd_business_id', Session::get('businessId'));
        }

        public function scopeOverlapping($query, $startDate, $endDate, $id = 0){
            $query->ofBusiness()->where('cd_start_date', '<=', $endDate)->where('cd_end_date', '>=', $startDate);
            if($id)
                $query->where('cd_id', '!=', $id);

            return $query;
        }
    /*
    **end: SCOPES
    */


    /*
    **start: FUNCTIONS
    */  
        /**
         *Check if exisiting closed dates overlap with given closed date
         *
         * @param date $startDate Start date of closing period
         * @param date $endDate End date of closing period
         * @param int $id PK of the closing period. Optional
         *
         * @return boolean
         */
        static function ifOverlapping($startDate, $endDate, $id = 0){
            return ClosedDate::Overlapping($startDate, $endDate, $id)->exists();
        }

        /**
         *Get exisiting closed dates that overlap with given closed date
         *
         * @param date $startDate Start date of closing period
         * @param date $endDate End date of closing period
         * @param int $id PK of the closing period. Optional
         *
         * @return array Overlapping closed dates
         */
        static function getOverlapping($startDate, $endDate, $id = 0){
            return ClosedDate::Overlapping($startDate, $endDate, $id)->select('cd_start_date', 'cd_end_date')->orderBy('cd_start_date')->get();
        }
    /*
    **end: FUNCTIONS
    */
}

?>
