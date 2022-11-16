<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesProcessProgress extends Model{
    use SoftDeletes;
	protected $table = 'sales_process_progresses';
	protected $primaryKey = 'spp_id';
    protected $dates = ['spp_comp_date'];

    const CONTACT = 1, BOOK_CONSULTATION= 2, CONSULTATION= 3, BOOK_BENCHMARK =4,BENCHMARK = 5,BOOK_TEAM1= 6,TEAM1= 11, BOOK_TEAM2= 7,TEAM2= 23  ,BOOK_TEAM3= 8,TEAM3= 24, BOOK_TEAM4= 9,TEAM4= 25 , BOOK_TEAM5= 10,TEAM5= 26,BOOK_INDIVIDUAL1= 12,INDIVIDUAL1= 17, BOOK_INDIVIDUAL2= 13,INDIVIDUAL2= 19  ,BOOK_INDIVIDUAL3= 14,INDIVIDUAL3= 20, BOOK_INDIVIDUAL4= 15,INDIVIDUAL4= 21 , BOOK_INDIVIDUAL5= 16,INDIVIDUAL5= 22;
    const EPIC_SALES_PROGRESS = 'EPIC SALES PROCESS';
    /*
    **start: ACCESSOR
    */
        public function getCompletedOnAttribute(){
            return $this->spp_comp_date->format('D, j M Y');
        }
    /*
    **end: ACCESSOR
    */


    /*
    **start: SCOPES
    */
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
    /*
    **end: RELATIONS
    */


    /*
    **start: FUNCTIONS
    */
        static function manualCompSteps($clientId, $type, $col){
            $book = $attend = [];
            /*$manualComp = SalesProcessProgress::where('spp_client_id', $clientId)->where('spp_comp_manual', 1)->select('spp_step_numb')->get();
            if($manualComp->count()){
                $manualComp = $manualComp->pluck('spp_step_numb')->toArray();*/
            $manualComp = SalesProcessProgress::manualCompStepsNumb($clientId);
            if(count($manualComp)){
                if($type == 'indiv'){
                    $bookingSteps = indivBookingSteps();
                    $attendSteps = indivAttendSteps();
                }
                else if($type == 'team'){
                    $bookingSteps = teamBookingSteps();
                    $attendSteps = teamAttendSteps();
                }
                
                foreach($manualComp as $step){
                    if(in_array($step, $bookingSteps)){
                        if($col == 'attendance')
                            $book[] = 'Booked';
                        else
                            $book[] = 0;
                    }
                    else if($col == 'attendance' && in_array($step, $attendSteps))
                        $attend[] = 'Attended';
                }

                //$book = ['Booked']; //18,20,22
                //$attend = ['Attended', 'Attended']; //17,19
                if($col == 'attendance'){
                    $indivCount = count($book);
                    $indivedCount = count($attend);
                    if($indivCount || $indivedCount){
                        if($indivedCount > $indivCount){
                            $result = array_splice($attend, $indivCount);
                            $book = $attend;
                            $attend = $result;
                        }
                        else{
                            $book = array_replace($book, $attend);
                            $attend = [];
                        }
                    }
                }
            }
            return ['book'=>$book, 'attend'=>$attend];
        }

        static function manualCompStepsNumb($clientId){
            $manualComp = SalesProcessProgress::where('spp_client_id', $clientId)->where('spp_comp_manual', 1)->select('spp_step_numb')->get();
            if($manualComp->count())
                return $manualComp->pluck('spp_step_numb')->toArray();
            return [];
        }
    /*
    **end: FUNCTIONS
    */


    /*
    **start: EVENTS
    */
    /*
    **END: EVENTS
    */
}
