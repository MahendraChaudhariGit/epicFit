<?php
namespace App\Http\Controllers;

use App\Clients;
use Carbon\Carbon;
use DB;
class DashboardController extends Controller{
   
  public function show(){
	  $count_preconsult = Clients::where('account_status','Pre-Consultation')->count();
	  $count_active = Clients::where('account_status','active')->count(); 
	  $count_other = Clients::where('account_status','!=','active')->where('account_status','!=','Pre-Consultation')->count(); 	 
	  $totalclients=$count_preconsult+$count_active+$count_other;
      $total_preconsult=$this->percentageCalculator($count_active,$totalclients);
	  $total_other=$this->percentageCalculator($count_other,$totalclients);
	  $total_active=$this->percentageCalculator($count_preconsult,$totalclients);
      $MaxNumofClients=0;
      for($i=0;$i<12;$i++){
      	$current = Carbon::now();
		$getMonth=$current->subMonth($i);
		$startOfMonth = $getMonth->StartOfMonth()->toDateTimeString();
		$endOfMonth = $getMonth->EndOfMonth()->toDateTimeString(); 
		$count_clients[] = Clients::where('created_at','>=',$startOfMonth)->where('created_at','<=',$endOfMonth)->count();		  
       if($MaxNumofClients<=$count_clients[$i]){
           $MaxNumofClients=$count_clients[$i];
          }
       }
       //dd($MaxNumofClients);
	  return view('dashboard.show',compact('count_preconsult','count_active','count_other','totalclients','total_preconsult','total_active','total_other','count_clients','MaxNumofClients'));
   }

   protected function percentageCalculator($data1,$data2){
       return number_format((($data1/$data2)*100),2);
    }
    
}
?>