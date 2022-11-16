<?php
namespace App\Http\Middleware;

use Closure;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\ClosedDateTrait;
// use App\Http\Traits\StaffEventClassTrait;
use Session;
use DB;
use App\ClientMember;
use App;

class MemberMiddleware{
    use ClientTrait, StaffEventsTrait, HelperTrait, ClosedDateTrait/*, StaffEventClassTrait*/;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        
        if($request->route()->getPrefix() != '/epic-super-admin'){
            if(Session::has('lockstatus')){
                $url = $request->route()->uri();
                if(($url == 'unlock/user' && $request->isMethod('post')) || $request->isMethod('get'));
                else return redirect()->back();
            }
        }
        $cronProcess = $request->has("cron") && $request->cron == 'called';
        if( $cronProcess && $request->has("bid") ){
            Session::put('businessId', $request->bid);
        }

        if($request->isMethod('get') && !$request->ajax() && Session::has('businessId')){
            if( $cronProcess ){
                /* start: Manage membership */
                $membs = DB::select("SELECT orderedMemb.* FROM (SELECT memb.id, memb.cm_client_id, memb.cm_status, memb.cm_due_date, memb.cm_end_date FROM client_membership AS memb INNER JOIN clients ON clients.id = cm_client_id WHERE business_id = ".Session::get('businessId')." and clients.account_status in ('active', 'contra') AND cm_status NOT IN ('Next', 'On Hold', 'Removed') AND memb.deleted_at IS NULL AND clients.deleted_at IS NULL ORDER BY memb.created_at DESC LIMIT 18446744073709551615) AS orderedMemb GROUP BY orderedMemb.cm_client_id HAVING orderedMemb.cm_end_date < NOW() OR orderedMemb.cm_due_date < NOW()");
                $ids = [];
                if(count($membs)){
                    foreach($membs as $memb){
                        //if($memb->cm_status != 'Removed')
                            $ids[] = $memb->id;
                    }

                    $membs = ClientMember::find($ids);
                    foreach($membs as $memb){
                        $this->manageClientMemb($memb);
                    }
                    dd($ids);
                }
                /* end: Manage membership */

                /* start: Fetch upcoming tasks and upcoming tasks reminder */
                    /*$upcomingTasksTimestamp = [];
                    $tasks = $this->categoryTask();
                    if($tasks->count()){
                        $tasks = $tasks->where('completed_by', 0);
                        if($tasks->count()){
                            foreach($tasks as $task){
                                $taskDatetime = strtotime($task->task_due_date.' '.$task->task_due_time);
                                $upcomingTasksTimestamp[$taskDatetime][] = $task->id;
                                
                                if($task->reminders->count()){    
                                    $reminder = $task->reminders->first();
                                    $remiderDatetime = strtotime($reminder->tr_datetime);
                                    $upcomingTasksTimestamp[$remiderDatetime][] = $task->id;
                                }
                            }
                            ksort($upcomingTasksTimestamp);
                        }
                    }*/
                    //$upcomingTasksTimestamp = [1=>['a', 'b', 'c', 'e'], 2=>['d', 'e', 'c'], 3=>['f'], 4=>['g']];
                    //Session::put('upcomingTasksTimestamp', $this->upcomingTasksTimestamp());
                /* end: Fetch upcoming tasks and upcoming tasks reminder */
            }
            else {
                /*Start: Delete those event witch have only data */
                    $this->deleteStaffEventSingleServices();
                /*End: Delete those event witch have only data */

                /* Start: set time zone in session valiable */    
                    $this->setTimeZone();
                /* End: set time zone in session valiable */ 

                /* Start: Set nex invoice id in salestool */
                    $this->setNextInvoiceId();
                /* End: Set nex invoice id in salestool   */


                /* Start: Update 'this' updated bookings */
                    //$this->updateThisOnlyBookings();
                /* End: Update 'this' updated bookings */
                /* Start: Clear old parent id of bookings */
                    //$this->updateThisOnlyBookings();
                    //$this->clearBookingOldPar();
                /* End: Clear old parent id of bookings */

            }
        }
        
        return $next($request);
    }
}
