<?php
namespace App\Http\Traits;

use App\ClosedDate;
use Carbon\Carbon;

trait ClosedDateTrait{
    /**
     * Get closed dates of business. It convert range into individual dates.
     *
     * @return string Dates with comma separated
     * 
     */ 
    protected function getClosedDates(){
        $closedDatesRec = ClosedDate::ofBusiness()->select('cd_start_date', 'cd_end_date')->get();
        return $this->calcClosedDates($closedDatesRec);
    }

    /**
     * Format DB fetched closed dates.
     *
     * @param collection $closedDatesRec Records fetched from DB
     *
     * @return string Dates with comma separated
     * 
     */ 
    protected function calcClosedDates($closedDatesRec){
        if($closedDatesRec->count()){
            $closedDatesArr = $closedDatesRec->toArray();
            $closedDates = [];
            foreach($closedDatesArr as $key => $value)
                $closedDates[] = implode(',', $this->getDatesFromRange($value['cd_start_date'], $value['cd_end_date']));
            
            $closedDates = implode(',', $closedDates);
        } 
        else         
            $closedDates = '';

        return $closedDates;
    }

    /**
     * Calculate dates between two particular dates
     *
     * @param date $startDate Start date of the range
     * @param date $endDate End date of the range
     *
     * @return array
     */ 
    protected function getDatesFromRange($startDate, $endDate){
        $dates = [];
        $startDate = new Carbon($startDate);
        $endDate = new Carbon($endDate);

        for($date = $startDate; $date->lte($endDate); $date->addDay())
            $dates[] = $date->toDateString();

        return $dates;
    }
}