<?php
namespace App\Http\Traits;
use Session;
use App\ChartSetting;
use Carbon\Carbon;

trait ChartSettingTrait{
    /**
     * Insert Calendar settings
     * @param void
     * @return boolean(true/false) 
     */
    protected function createChartSetting(){
        $timestamp = Carbon::now();

    	$clientsChart = array(
                            "active"=>"#253746",
                            "contra"=>"#00503c",
                            "inactive"=>"#ff9933",
                            "on_hold"=>"#004080",
                            "pending"=>"#e6dacf",
                            "other"=>"#d3d2f2"
                        );

    	$salesProChart = array(
                            "sales_pending"=>"#004080", 
                            "pre_consultation"=>"#ff0000", 
                            "pre_benchmark"=>"#800040", 
                            "pre_training"=>"#c4d7dd"
                        );    

    	$data = array(
    				array('chart_business_id'=>Session::get('businessId'),'chart_type'=>'clientsChart','chart_setting_data'=>json_encode($clientsChart), 'created_at'=>$timestamp, 'updated_at'=>$timestamp),
    				array('chart_business_id'=>Session::get('businessId'),'chart_type'=>'salesProChart','chart_setting_data'=>json_encode($salesProChart), 'created_at'=>$timestamp, 'updated_at'=>$timestamp)
    		    );
    	
	    if(ChartSetting::Insert($data)){
	    	return true;
	    }
	    else
	    	return false;
    }
}