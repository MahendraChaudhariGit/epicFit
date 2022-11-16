<?php
namespace App\Http\Traits\Result;
use DB;
use Session;
use App\Result\Clients;
use App\Result\MemberShipTax;

trait ApiTrait{
    /**
     * Api functions
    */
     

    /**
     * Copy delete class client
     * @param class value, makeup(true/false)
     * @return void
    **/
    protected function copyDeletedClassClients($colsVal, $makeup = false){
        if(is_array($colsVal)){
            $where = "secc_sec_id = $colsVal[0] and secc_client_id in (".implode(',', $colsVal[1]).")";
            if($makeup)
                $where .= " and secc_if_make_up = 1";
        }
        else{
            $where = "(secc_sec_id, secc_client_id";
            if($makeup)
                $where .= ", secc_if_make_up";
            $where .= ") in ($colsVal)";
        }
       
        DB::statement("INSERT INTO deleted_event_class_clients (`secc_id`, `secc_sec_id`, `secc_client_id`, `secc_notes`, `secc_reduce_rate_session`, `secc_if_recur`, `secc_if_make_up`, `secc_if_make_up_created`, `secc_is_make_up_client`, `secc_client_attendance`, `secc_client_status`, `created_at`, `updated_at`, `deleted_at`) SELECT `secc_id`, `secc_sec_id`, `secc_client_id`, `secc_notes`, `secc_reduce_rate_session`, `secc_if_recur`, `secc_if_make_up`, `secc_if_make_up_created`, `secc_is_make_up_client`, `secc_client_attendance`, `secc_client_status`, `created_at`, `updated_at`, `deleted_at` FROM staff_event_class_clients where $where");
    }


    /**
     * Delete Added Clients MakeUp From Same Event
     * @param class id, event id
     * @return void
    **/
    protected function deleteAdded_clientsMakeUpFromSameEvent($eventId, $clientsId){
        $this->copyDeletedClassClients([$eventId, $clientsId], 1);
        DB::table('staff_event_class_clients')->where('secc_sec_id', $eventId)->whereIn('secc_client_id', $clientsId)->where('secc_if_make_up', 1)->delete();
    }


    /**
     * Mark Class Client As MakeUp Created
     * @param makup client id
     * @return void
    **/
    protected function markClass_clientAsMakeUpCreated($makeUpClientsId){
        if(count($makeUpClientsId)){
            $classClientRecs = DB::select(DB::raw("(SELECT classClientRec.* FROM (SELECT secc_id, secc_client_id FROM staff_event_class_clients WHERE secc_client_id in (".implode(',', $makeUpClientsId).") AND secc_if_make_up = 1 AND secc_if_make_up_created = 0 ORDER BY secc_sec_id LIMIT 18446744073709551615) as classClientRec GROUP BY classClientRec.secc_client_id) "));

            $doneMakeUpOfClients = [];
            if(count($classClientRecs)){
                $makeUpEventsId = [];
                foreach($classClientRecs as $classClientRec){
                    $makeUpEventsId[] = $classClientRec->secc_id;
                    $doneMakeUpOfClients[] = $classClientRec->secc_client_id;
                }
                DB::table('staff_event_class_clients')->whereIn('secc_id', $makeUpEventsId)->update(['secc_if_make_up_created' => 1, 'updated_at' => createTimestamp()]);
            }

            $decMakeUpCountOfClients = [];
            if(count($doneMakeUpOfClients))
                $decMakeUpCountOfClients = array_diff($makeUpClientsId, $doneMakeUpOfClients);
            else
                $decMakeUpCountOfClients = $makeUpClientsId;

            if(count($decMakeUpCountOfClients))
                Clients::whereIn('id', $decMakeUpCountOfClients)->decrement('makeup_session_count');
        }
    }


    /**
     * Get tax type and its value
     * @param businessid, salestoolsinvoiceObj, taxType, price
     * @return data in array
    **/
    protected function getTaxData($businessid, $salestoolsinvoiceObj, $taxType, $price){
        if($salestoolsinvoiceObj){
            $taxLabel = 'N/A';
            $taxValue = 0;
            $taxType = strtolower($taxType);

            if($taxType=='including'){
                if(count($salestoolsinvoiceObj)){
                    $tax = MemberShipTax::where('mtax_business_id',$businessid)->select('id','mtax_label','mtax_rate')->where('id',$salestoolsinvoiceObj->sti_override)->first();
                    if(count($tax)){
                        $taxLabel = $tax->mtax_label;
                        $taxValue = $price-($price/(1+($tax->mtax_rate/100)));
                    }
                }

                $unitPrice = $price - $taxValue;
                $totalWithTax = $price;
                $taxtype = 'Including';
            }
            elseif($taxType=='excluding'){
                if(count($salestoolsinvoiceObj)){
                    $tax = MemberShipTax::where('mtax_business_id',$businessid)->select('id','mtax_label','mtax_rate')->where('id',$salestoolsinvoiceObj->sti_override)->first();
                    if(count($tax)){
                        $taxLabel = $tax->mtax_label;
                        $taxValue = $price*($tax->mtax_rate/100);
                    }
                }

                $unitPrice = $price;
                $totalWithTax = $price + $taxValue;
                $taxtype = 'Excluding';
            }else{
                $unitPrice = $price;
                $totalWithTax = $price;
                $taxtype = 'N/A';
            }
            return array('unitPrice'=>$unitPrice, 'totalWithTax'=>$totalWithTax, 'taxValue'=>$taxValue, 'taxtype'=>$taxtype, 'taxLabel'=>$taxLabel); 
        }
        return array('unitPrice'=>$price, 'totalWithTax'=>$price, 'taxValue'=>0, 'taxtype'=>'N/A', 'taxLabel'=>$taxLabel);
    }

    
}