<?php
namespace App\Events\Frontend\Auth;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Session;
use App\Business;
use DB;
use App\LocationArea;
use App\Http\Traits\CalendarSettingTrait;
use App\Http\Traits\ChartSettingTrait;
use App\Http\Traits\SalesTools\Invoice\SalesToolsInvoiceTrait;
use App\Http\Traits\HelperTrait;
/**
 * Class UserLoggedIn
 * @package App\Events\Frontend\Auth
 */
class UserLoggedIn extends Event{
    use SerializesModels, CalendarSettingTrait, SalesToolsInvoiceTrait, ChartSettingTrait, HelperTrait;

    public $user;

    public function __construct($user){
        $this->user = $user;
        //Session::put('userType', $this->user->account_type);  

        $business =  Business::find($this->user->business_id);
        if($business){
            Session::put('businessId', $business->id);
            Session::put('hostname', 'crm');
            //Session::put('timeZone', $business->time_zone);

            if($business->locations()->exists())
                Session::put('ifBussHasLocations', true);

            if(LocationArea::join('locations', 'la_location_id', '=', 'id')->where('business_id', $business->id)->whereNull('locations.deleted_at')->whereNull('location_areas.deleted_at')->count())
                Session::put('ifBussHasAreas', true);

            if($business->staffs()->exists())
                Session::put('ifBussHasStaffs', true);

            if($business->services()->exists())
                Session::put('ifBussHasServices', true); 

            if($business->classes()->exists())
                Session::put('ifBussHasClasses', true); 

            if($business->products()->exists())
                Session::put('ifBussHasProducts', true); 

            if($business->clients()->exists())
                Session::put('ifBussHasClients', true); 

            if($business->contacts()->exists())
                Session::put('ifBussHasContacts', true); 

            if($business->salesToolsDiscounts()->exists())
                Session::put('ifBussHasSalesToolsDiscounts', true);

            if($business->resources()->exists())
                Session::put('ifBussHasResources', true);

            if($business->closedDates()->exists())
                Session::put('ifBussHasClosedDates', true);

            /*if($business->administrators()->exists())
                Session::put('ifBussHasAdministrators', true);*/
            if($business->administrators($business->id, $business->user_id))
                Session::put('ifBussHasAdministrators', true);

            /*if($business->salesToolsInvoice()->exists())
                Session::put('ifBussHasSalesToolsInvoice', true);*/

            if($business->user_id == $this->user->id)
                Session::put('isSuperUser', true); 
            else
                Session::put('isSuperUser', false); 
            /*Start: check callender setting record in db or no if no its create callender setting.*/
            /*$calendarID = CalendarSetting::select('id')->where('cs_business_id',$business->id)->first();*/
            if(!$business->calendarSetting()->exists())
                $this->createCalendarSettings();
            
            /*End: check callender setting record in db or no if no its create callender setting.*/

            if(!$business->salestoolsInvoice()->exists())
                $this->createInvoice();

            if(!$business->chartSetting()->exists())
                $this->createChartSetting();

            //$tz=$business->pluck('time_zone');
            Session::put('timeZone', $business->time_zone);
            $this->setTimeZone();
             
            
        }

        /*if(!Session::has('businessId')){
            $business = $this->user->businesses;
            if($business)
                Session::put('businessId' , $business->id);  
        }
		if(!Session::has('ifBussHasLocations') && Session::has('businessId')){
            $locations = Business::find(Session::get('businessId'))->locations;
            if(count($locations))
                Session::put('ifBussHasLocations', true);  
        }
        if(!Session::has('ifBussHasStaffs') && Session::has('businessId')){
            $staffs = Business::find(Session::get('businessId'))->staffs;
            if(count($staffs))
                Session::put('ifBussHasStaffs', true);  
        }
        if(!Session::has('ifBussHasServices') && Session::has('businessId')){
            $services = Business::find(Session::get('businessId'))->services;
            if(count($services))
                Session::put('ifBussHasServices', true);  
        }
        if(!Session::has('ifBussHasClasses') && Session::has('businessId')){
            $classes = Business::find(Session::get('businessId'))->classes;
            if(count($classes))
                Session::put('ifBussHasClasses', true);  
        }
        if(!Session::has('ifBussHasProducts') && Session::has('businessId')){
            $products = Business::find(Session::get('businessId'))->products;
            if(count($products))
                Session::put('ifBussHasProducts', true);  
        }
        if(!Session::has('ifBussHasClients') && Session::has('businessId')){
            $clients = Business::find(Session::get('businessId'))->clients;
            if(count($clients))
                Session::put('ifBussHasClients', true);  
        }
        if(!Session::has('ifBussHasContacts') && Session::has('businessId')){
            $contacts = Business::find(Session::get('businessId'))->contacts;
            if(count($contacts))
                Session::put('ifBussHasContacts', true);    
        }*/
    }
}