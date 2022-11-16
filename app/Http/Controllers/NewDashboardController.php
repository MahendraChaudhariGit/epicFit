<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Clients;
use Carbon\Carbon;
use DB;
use Session;
use App\Parq;
use App\Business;
use App\Service;
use App\Task;
use App\TaskReminder;
use App\StaffEventRepeat;
use App\TaskCategory;
use Illuminate\Http\Request;
use Auth;
use App\Http\Traits\HelperTrait;
use Input;
use App\Http\Traits\StaffEventsTrait;
use App\StaffEventSingleService;
use App\StaffEventClass;
use App\StaffEventBusy;
use App\Staff;
use App\ChartSetting;
use App\Clas;
use Config;
use DateTimeZone;
use DateTime;
use App\Models\Access\User\User;
use App\TaskRepeat;
use App\ClientAccountStatusGraph;
use App\StaffEventHistory;
use Cache;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class NewDashboardController extends Controller
{
  use StaffEventsTrait, HelperTrait;

  /**
   * Show dashboard view
   *
   * @param void
   * @return dashboard view
   */
  public function show()
  {
    if (Session::has('businessId')) {
      $business = Business::findOrFail(Session::get('businessId'));
      $countries = \Country::getCountryLists();
      $business->stateName = \Country::getStateName($business->country, $business->state);
      $business->currencyInFull = \Currency::$currencies[$business->currency];
      $default_completed_service = Service::defaultAndComplCount();


      $this->neverEndTaskRepeats();
      $taskcategories = TaskCategory::where('t_cat_business_id', $business->id)->orWhere('t_cat_business_id', 0)->orderBy('id', 'asc')->get();

      $personalCatId = $taskcategories->where('t_cat_business_id', 0)->where('t_cat_user_id', 0)->pluck('id')->first();
      $tasks = $this->categoryTask($personalCatId);

      $bussUsers = [];
      if (isSuperUser()) {
        $bussUsers = User::where('business_id', Session::get('businessId'))->whereIn('account_type', ['Admin', 'Staff'])->where('id', '!=', Auth::id())->get();
      }

      $newdata = [];
      $newdata = $this->getTc($taskcategories, $bussUsers);
      $tc = [];
      $tc = $newdata['tc'];
      $eventRepeatIntervalOpt = $newdata['eventRepeatIntervalOpt'];
    } else {
      $business = null;
      $countries = [];
      $default_completed_service = 0;
    }

    $MaxNumofClients = 0;
    if (Session::has("ifBussHasClients")) {
      //Start: pie chart 1
      $count_active = Clients::ofBusiness()->where('account_status', 'Active')->count();
      $count_contra = Clients::ofBusiness()->where('account_status', 'Contra')->count();
      $count_inactive = Clients::ofBusiness()->where('account_status', 'Inactive')->count();
      $count_onhold = Clients::ofBusiness()->where('account_status', 'On Hold')->count();
      $count_pending = Clients::ofBusiness()->where('account_status', 'Pending')->count();
      $count_other = Clients::ofBusiness()->where('account_status', '!=', 'Active')->where('account_status', '!=', 'Contra')->where('account_status', '!=', 'Inactive')->where('account_status', '!=', 'On Hold')->where('account_status', '!=', 'Pending')->count();
      $totalclients = $count_active + $count_contra + $count_inactive + $count_onhold + $count_pending + $count_other;

      $total_active = $this->percentageCalculator($count_active, $totalclients);
      $total_contra = $this->percentageCalculator($count_contra, $totalclients);
      $total_inactive = $this->percentageCalculator($count_inactive, $totalclients);
      $total_onhold = $this->percentageCalculator($count_onhold, $totalclients);
      $total_pending = $this->percentageCalculator($count_pending, $totalclients);
      $total_other = $this->percentageCalculator($count_other, $totalclients);
      //End: pie chart 1

      //Start: pie chart 2
      $count_lead = Clients::ofBusiness()->where('account_status', 'Pending')->count();
      $count_pre_preconsult = Clients::ofBusiness()->where('account_status', 'Pre-Consultation')->count();
      $count_pre_benchmark = Clients::ofBusiness()->where('account_status', 'Pre-Benchmarking')->count();
      $count_pre_training = Clients::ofBusiness()->where('account_status', 'Pre-Training')->count();

      $totalclients2 = $count_lead + $count_pre_preconsult + $count_pre_benchmark + $count_pre_training;
      $total_lead = $this->percentageCalculator($count_lead, $totalclients);
      $total_pre_preconsult = $this->percentageCalculator($count_pre_preconsult, $totalclients);
      $total_pre_benchmark = $this->percentageCalculator($count_pre_benchmark, $totalclients);
      $total_pre_training = $this->percentageCalculator($count_pre_training, $totalclients);
      //End: pie chart 2

      //Start : Graph Chart

      $MaxNumofPerks = 0;
      for ($i = 0; $i < 12; $i++) {
        $current = Carbon::now();
        $getMonth = $current->subMonth($i);

        $startOfMonth = $getMonth->StartOfMonth()->toDateTimeString();
        $endOfMonth = $getMonth->EndOfMonth()->toDateTimeString();
        //$count_clients[] = Clients::ofBusiness()->where('created_at','>=',$startOfMonth)->where('created_at','<=',$endOfMonth)->count();

        // $count_inactive_clients[] = Clients::ofBusiness()->where('account_status','Inactive')->where('created_at','>=',$startOfMonth)->where('created_at','<=',$endOfMonth)->count();
        $count_inactive_clients[] = ClientAccountStatusGraph::where('business_id', Session::get('businessId'))->where('account_status', 'Inactive')
          ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
          ->count();

        // $count_onhold_clients[] = Clients::ofBusiness()->where('account_status','On Hold')->where('created_at','>=',$startOfMonth)->where('created_at','<=',$endOfMonth)->count();
        $count_onhold_clients[] = ClientAccountStatusGraph::where('business_id', Session::get('businessId'))->where('account_status', 'On Hold')
          ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
          ->count();

        // $count_new_client[] = Parq::ofBusiness()->where('waiverDate','>=',$startOfMonth)->where('waiverDate','<=',$endOfMonth)->where('waiverDate','!=','0000-00-00')->count();
        // $count_new_client[] = Clients::ofBusiness()->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $count_new_client[] = ClientAccountStatusGraph::where('business_id', Session::get('businessId'))->where('account_status', 'Active')
          ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
          ->count();
      }

      $MaxNumofClients = max(max($count_inactive_clients), max($count_new_client), max($count_onhold_clients));

      //End : Graph Chart

    } else {
      $count_active = $count_contra = $count_inactive = $count_onhold = $count_pending = $count_other = $totalclients = $total_active = $total_contra = $total_inactive = $total_onhold = $total_pending = $total_other = $count_lead = $count_pre_preconsult = $count_pre_benchmark = $count_pre_training = $totalclients2 = $total_lead = $total_pre_preconsult = $total_pre_benchmark = $total_pre_training =/*$MaxNumofClients=*/ 0;
      $count_inactive_clients = $count_onhold_clients = $count_new_client = [];
    }
    if (!$MaxNumofClients)
      $MaxNumofClients = 5;
    /*Start: ---- SALES and PRODUCTIVITY Stack bar ------ */

    $dt = Carbon::now();
    $ad = $dt->addDays(7)->toDateString();
    $dt = Carbon::now();
    $sd = $dt->subDays(6)->toDateString();

    $service_details = StaffEventSingleService::select('sess_id', 'sess_booking_status', 'sess_date', 'sess_price', 'sess_duration', 'sess_client_attendance')->where('sess_business_id', Session::get('businessId'))->where('sess_date', '>=', $sd)->where('sess_date', '<=', $ad)->orderBy('sess_date')->get();

    $class_details = StaffEventClass::with(/*array(*/'clients'/*=>function($query){$query->sele;})*/)->select('sec_id', 'sec_date', 'sec_price', 'sec_duration')->where('sec_business_id', Session::get('businessId'))->where('sec_date', '>=', $sd)->where('sec_date', '<=', $ad)->orderBy('sec_date')->get();

    $busyTime = StaffEventBusy::select('seb_duration', 'seb_date')->where('seb_business_id', Session::get('businessId'))->where('seb_date', '>=', $sd)->where('seb_date', '<=', $ad)->orderBy('seb_date')->get();

    $averageSale = StaffEventSingleService::where('sess_business_id', Session::get('businessId'))
      ->where('sess_client_attendance', 'Attended')
      ->avg('sess_price');
    $cd = Carbon::now();
    $last7daysAverageSale = StaffEventSingleService::where('sess_business_id', Session::get('businessId'))
      ->where('sess_client_attendance', 'Attended')
      ->where('sess_date', '>=', $sd)
      ->where('sess_date', '<=', $cd)
      ->avg('sess_price');

    $staff_working_hour = Staff::select('staff.id', 'sa_start_time', 'sa_end_time', 'edited_start_time', 'edited_end_time', 'sa_date')
      ->leftJoin('staff_attendences', function ($join) {
        $join->on('staff.id', '=', 'staff_attendences.sa_staff_id');
      })
      ->where('business_id', Session::get('businessId'))
      ->where('sa_status', '<>', 'unattended')
      ->where('sa_date', '>=', $sd)
      ->where('sa_date', '<=', $ad)
      ->get();


    $datewise_working_hour = [];
    if (count($staff_working_hour)) {
      foreach ($staff_working_hour as  $value) {
        if ($value->edited_start_time != null) {
          if (array_key_exists($value->sa_date, $datewise_working_hour))
            $datewise_working_hour[$value->sa_date] += ((strtotime($value->edited_end_time) - strtotime($value->edited_start_time)) / 60);
          else
            $datewise_working_hour[$value->sa_date] = ((strtotime($value->edited_end_time) - strtotime($value->edited_start_time)) / 60);
        } else {
          if (array_key_exists($value->sa_date, $datewise_working_hour))
            $datewise_working_hour[$value->sa_date] += ((strtotime($value->sa_end_time) - strtotime($value->sa_start_time)) / 60);
          else
            $datewise_working_hour[$value->sa_date] = ((strtotime($value->sa_end_time) - strtotime($value->sa_start_time)) / 60);
        }
      }
    }
    $week_working_hour = Staff::select('id', 'hr_day', /*DB::raw('SUM(TIMESTAMPDIFF(MINUTE, hr_start_time, hr_end_time)) AS workingTime'))*/ DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(hr_end_time, hr_start_time))/60) AS workingTime'))
      ->leftJoin('hours', function ($join) {
        $join->on('staff.id', '=', 'hours.hr_entity_id');
      })
      ->where('business_id', Session::get('businessId'))
      ->where('hr_entity_type', 'staff')
      ->groupBy('hr_day')
      ->get();
    /* End: fatch total working hour */

    /*Start: Loop for find no. of clients and multiplay with price*/
    $datewise_class_val = [];
    $datewise_cls_time = [];
    $date_exists = [];
    if (count($class_details)) {
      foreach ($class_details as $key => $value) {
        if (array_key_exists($value->sec_date, $datewise_cls_time))
          $datewise_cls_time[$value->sec_date] += $value->sec_duration;
        else
          $datewise_cls_time[$value->sec_date] = $value->sec_duration;
        $price = 0;
        foreach ($value->clients as $keys => $val) {
          if ($val->pivot->secc_reduce_rate != null) {
            $price += $val->pivot->secc_reduce_rate;
          } else {
            $price += $value->sec_price;
          }
        }
        if (array_key_exists($value->sec_date, $datewise_class_val))
          $datewise_class_val[$value->sec_date] += round($price);
        else
          $datewise_class_val[$value->sec_date] = round($price);
      }
    }

    /*Start: calculate busy time acording to date.*/
    $datewise_busy_time = [];
    if (count($busyTime)) {
      foreach ($busyTime as $key => $value) {
        $datewise_busy_time[$value->seb_date] = $value->seb_duration;
      }
    }
    /*End: calculate busy time acording to date.*/

    $confirmed_value = [];
    $pencil_value = [];
    $conf_time = [];
    $pencil_time = [];
    $attended_time = [];
    $conf_pre_val = [];
    $pencil_pre_val = [];
    $notshow_pre_val = [];
    $datewise_conf_val = [];
    $datewise_pencil_val = [];
    $datewise_conf_time = [];
    $datewise_pencil_time = [];
    $datewise_notshow_time = [];
    $datewise_attended_time = [];
    $notshow_time = [];
    $i = -1;
    $j = -1;
    $k = -1;
    $m = -1;
    if (count($service_details)) {
      foreach ($service_details as $key => $value) {
        if ($value->sess_booking_status == 'Confirmed') {
          if ($value->sess_client_attendance != 'Did not show') {
            if (array_key_exists($value->sess_date, $datewise_conf_val)) {
              $datewise_conf_val[$value->sess_date] += floor($value->sess_price);
            } else {
              $datewise_conf_val[$value->sess_date] = floor($value->sess_price);
            }

            if ($value->sess_client_attendance == 'Attended') {
              if (array_key_exists($value->sess_date, $datewise_attended_time)) {
                $datewise_attended_time[$value->sess_date] += $value->sess_duration;
              } else {
                $datewise_attended_time[$value->sess_date] = $value->sess_duration;
              }
            }
            if ($value->sess_client_attendance == 'Booked') {
              if (array_key_exists($value->sess_date, $datewise_conf_time)) {
                $datewise_conf_time[$value->sess_date] += $value->sess_duration;
              } else {
                $datewise_conf_time[$value->sess_date] = $value->sess_duration;
              }
            }
          } else {
            if (in_array($value->sess_date, $notshow_pre_val)) {
              $notshow_time[$k] += $value->sess_duration;
            } else {
              $k++;
              $notshow_time[$k] = $value->sess_duration;
              $notshow_pre_val[] = $value->sess_date;
            }
            $datewise_notshow_time[$value->sess_date] = $notshow_time[$k];
          }
        } elseif ($value->sess_booking_status == 'Pencilled-In') {
          if (in_array($value->sess_date, $pencil_pre_val)) {
            $pencil_value[$j] += floor($value->sess_price);
            $pencil_time[$j] += $value->sess_duration;
          } else {
            $j++;
            $pencil_value[$j] = floor($value->sess_price);
            $pencil_time[$j] = $value->sess_duration;
            $pencil_pre_val[] = $value->sess_date;
          }

          $datewise_pencil_val[$value->sess_date] = $pencil_value[$j];
          $datewise_pencil_time[$value->sess_date] = $pencil_time[$j];
        }
      }
    }

    $dt = date('Y-m-d');
    $md = date('Y-m-d', strtotime("$dt -6 day"));

    $cls_time = [];
    $busy_time = [];
    $attended_time = [];
    $notshow_time = [];
    $conf_time = [];
    $pen_time = [];
    $total_working_time = [];
    $final_conf = [];
    $final_pencil = [];
    $sd = $md;
    /* Start: fatch data datewise and store value in index array  */
    for ($i = 0; $i < 14; $i++) {
      /*------ for confirmed and class total sales value ------*/
      if (array_key_exists($sd, $datewise_conf_val) && array_key_exists($sd, $datewise_class_val)) {
        $final_conf[] = ($datewise_conf_val[$sd] + $datewise_class_val[$sd]);
      } elseif (array_key_exists($sd, $datewise_conf_val)) {
        $final_conf[] = $datewise_conf_val[$sd];
      } elseif (array_key_exists($sd, $datewise_class_val)) {
        $final_conf[] = $datewise_class_val[$sd];
      } else {
        $final_conf[] = 0;
      }
      /*------ for penciled-In total sales value -----*/
      if (array_key_exists($sd, $datewise_pencil_val)) {
        $final_pencil[] = $datewise_pencil_val[$sd];
      } else {
        $final_pencil[] = 0;
      }
      /*----- for confirmed total time -----*/
      if (array_key_exists($sd, $datewise_conf_time)) {
        $conf_time[] = $datewise_conf_time[$sd];
      } else {
        $conf_time[] = 0;
      }
      /*----- for penciled-in total time -------*/
      if (array_key_exists($sd, $datewise_pencil_time)) {
        $pen_time[$i] = $datewise_pencil_time[$sd];
      } else {
        $pen_time[] = 0;
      }
      /*------ for did not show total time ------ */
      if (array_key_exists($sd, $datewise_notshow_time)) {
        $notshow_time[] = $datewise_notshow_time[$sd];
      } else {
        $notshow_time[] = 0;
      }
      /*---- for attended total time ------*/
      if (array_key_exists($sd, $datewise_attended_time)) {
        $attended_time[] = $datewise_attended_time[$sd];
      } else {
        $attended_time[] = 0;
      }
      /*----------- for busy time ---------------*/
      if (array_key_exists($sd, $datewise_busy_time)) {
        $busy_time[] = $datewise_busy_time[$sd];
      } else {
        $busy_time[] = 0;
      }
      /*-------- for class time ------------*/
      if (array_key_exists($sd, $datewise_cls_time)) {
        $cls_time[] = $datewise_cls_time[$sd];
      } else {
        $cls_time[] = 0;
      }
      /* ----for daywise working hour -----*/
      if (array_key_exists($sd, $datewise_working_hour)) {
        $total_working_time[] = $datewise_working_hour[$sd];
      } elseif (count($week_working_hour)) {
        $day_name = date('l', strtotime($sd));
        $record = $week_working_hour->where('hr_day', $day_name)->first();
        if ($record) {
          $total_working_time[] = (int) $record->workingTime;
        } else {
          $total_working_time[] = 0;
        }
      } else {
        $total_working_time[] = 0;
      }
      /*---Increse one day in every cycle----*/
      $sd = date('Y-m-d', strtotime("$sd +1 day"));
    }
    /* End: fatch data datewise and store value in index array  */

    $maxTime = ceil(max($total_working_time) / 60);
    if ($maxTime <= 36)
      $maxTime = 36;
    $max_conf = max($final_conf);
    $max_pencil = max($final_pencil);
    $maxVal = $max_pencil + $max_conf;
    if (!$maxVal)
      $maxVal = 8;
    /*Start: get previous week total service peice */
    $last_14days_price = 0;
    $last_7days_price = 0;
    if (Session::has('businessId')) {
      $monday_date = date('Y-m-d', strtotime("previous monday"));
      $sales = DB::table('sales')->select('sal_id', 'sal_services', 'sal_total')
        ->where('sal_weekDate', $monday_date)
        ->where('sal_business_id', Session::get('businessId'))
        ->first();
      if (count($sales)) {
        $last_7days_price = $sales->sal_services;
        $total_7days_price = $sales->sal_total;
      } else {
        $dt = Carbon::now();
        $last_14days = $dt->subDays(13)->toDateString();
        $dt = Carbon::now();
        $last_7days = $dt->subDays(6)->toDateString();
        $current_day = Carbon::now()->toDateString();

        $salesprice = StaffEventSingleService::select('sess_id', 'sess_price', 'sess_date')->where('sess_client_attendance', 'Attended')->where('sess_business_id', Session::get('businessId'))->where('sess_date', '>=', $last_14days)->where('sess_date', '<=', $current_day)->orderBy('sess_date')->get();

        foreach ($salesprice as $key => $value) {
          if ($value->sess_date < $last_7days) {
            $last_14days_price += $value->sess_price;
          } else {
            $last_7days_price += $value->sess_price;
          }
        }
        $data = [];
        $data['sal_business_id'] = Session::get('businessId');
        $data['sal_weekDate'] = $monday_date;
        $data['sal_services'] = $last_7days_price;
        $data['created_at'] = createTimestamp();;
        if (count($data))
          DB::table('sales')->insert($data);
      }
    }
    /*End: get previous week total service peice */
    /*End: ---- SALES Stack bar ------ */

    /* Start: chart setting */
    $chartsetting = ChartSetting::select('chart_type', 'chart_setting_data')->where('chart_business_id', Session::get('businessId'))->get();
    if (count($chartsetting)) {
      $clients_chart = $chartsetting->where('chart_type', 'clientsChart')->first();
      $sales_chart = $chartsetting->where('chart_type', 'salesProChart')->first();

      $clients_chart = $clients_chart->chart_setting_data;
      $sales_chart = $sales_chart->chart_setting_data;
    }

    /* Start: Business Users Limit */
    $usersLimitData = null;
    if ($business) {
      $adminUser = User::with('usersLimit')->find($business->user_id);
      if ($adminUser->usersLimit != null) {
        $usersLimitData['usersLimit'] = $adminUser->usersLimit->maximum_users;
        $usersLimitData['price'] = $adminUser->usersLimit->price;
      }
    }
    /* End: Business Users Limit */

    if (isUserType(['Admin'])) {
      return view('dashboard.show', compact('count_active', 'count_contra', 'count_inactive', 'count_onhold', 'count_pending', 'count_other', 'totalclients', 'total_active', 'total_contra', 'total_inactive', 'total_onhold', 'total_pending', 'total_other', 'count_lead', 'count_pre_preconsult', 'count_pre_benchmark', 'count_pre_training', 'totalclients2', 'total_lead', 'total_pre_preconsult', 'total_pre_benchmark', 'total_pre_training', 'count_inactive_clients', 'count_onhold_clients', 'MaxNumofClients', 'count_new_client', 'business', 'countries', 'default_completed_service', 'tasks', 'taskcategories', 'bussUsers', 'eventRepeatIntervalOpt', 'tc', 'reminderdata', 'final_conf', 'final_pencil', 'maxVal', 'pen_time', 'conf_time', 'notshow_time', 'attended_time', 'busy_time', 'cls_time', 'last_14days_price', 'last_7days_price', 'total_working_time', 'averageSale', 'last7daysAverageSale', 'maxTime', 'clients_chart', 'sales_chart', 'usersLimitData'));
    } else {
      return view('dashboard.staffshow', compact('business', 'tasks', 'taskcategories', 'bussUsers', 'eventRepeatIntervalOpt', 'tc', 'reminderdata'));
    }
  }

  protected function getTc($taskcategories, $bussUsers = [])
  {
    $eventRepeatIntervalOpt = [
      "" => "-- Select --", 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6,
      7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15, 16 => 16, 17 => 17,
      18 => 18, 19 => 19, 20 => 20, 21 => 21, 22 => 22, 23 => 23, 24 => 24, 25 => 25, 26 => 26, 27 => 27,
      28 => 28, 29 => 29, 30 => 30, 31 => 31
    ];

    $tc = [];
    $tc[""] = "-- Select --";
    foreach ($taskcategories->sortBy('t_cat_name') as $categories) {
      $tc[$categories->id] = $categories->t_cat_name;
    }

    if (count($bussUsers)) {
      $commonCategory = $taskcategories->where('t_cat_user_id', 0)->where('t_cat_business_id', 0)->first();
      foreach ($bussUsers->sortBy('FullName') as $bussUser) {
        $tc[$commonCategory->id . '|' . $bussUser->id] = $bussUser->FullName . "'s " . $commonCategory->t_cat_name;
      }
    }

    //dd($tc);

    $data = [];
    $data['tc'] =  $tc;
    $data['eventRepeatIntervalOpt'] = $eventRepeatIntervalOpt;
    return $data;
  }

  protected function percentageCalculator($data1, $data2)
  {
    if ($data1)
      return number_format((($data1 / $data2) * 100), 2);
    else return 0;
  }

  public function storecat(Request $request)
  {
    //dd(Session::get('account_id'));
    $input = $request->categoryName;

    if ($request->hiddenCategId) {
      $insertData = array('t_cat_name' => $input);
      TaskCategory::where('t_cat_business_id', Session::get('businessId'))->where('id', $request->hiddenCategId)->update($insertData);
      $categ = array('id' => $request->hiddenCategId, 't_cat_name' => $input, 'categStatus' => 'updated');
    } else {
      $insertData = array('t_cat_business_id' => Session::get('businessId'), 't_cat_name' => $input);
      //$addedcat = TaskCategory::create($insertData);
      $addedcat = Auth::user()->taskCategory()->create($insertData);
      if ($addedcat)
        $categ = array('id' => $addedcat->id, 't_cat_name' => $addedcat->t_cat_name, "categStatus" => "created", 't_cat_user_id' => $addedcat->t_cat_user_id);
      else
        $categ = array("categStatus" => "fail");
    }
    echo json_encode($categ);
  }

  /**
   * storetask
   *
   * @param Request
   * @return Response
   */
  public function storetask(Request $request)
  {
    $reminder = Carbon::parse($request->reminderDateTime)->toDateTimeString();
    $businessId = Session::get('businessId');
    $taskDueDate = $this->calcEventDate($request, $request->taskDueDate);
    $taskFormId = $request->taskFormId;

    $insertData = array('task_business_id' => $businessId, 'task_name' => $request->taskName, 'task_due_date' => $taskDueDate, 'task_category' => $request->taskCategory, 'task_due_time' => $request->taskDueTime, 'task_user_id' => $request->authId, 'task_note' => $request->taskNote);

    /* Store new Task recored */
    if ($taskFormId == "") {
      $addedtask = Task::create($insertData);
      if ($request->has('isReminderSet')) {
        $timestamp = createTimestamp();
        TaskReminder::insert(['tr_is_set' => $request->isReminderSet, 'tr_hours' => $request->reminderHours, 'tr_datetime' => $reminder, 'tr_task_id' => $addedtask->id, 'created_at' => $timestamp]);
      }

      /* Save task repeat data */
      if ($request->eventRepeat != '' && ($request->eventRepeat == 'Daily' || $request->eventRepeat == 'Weekly' || $request->eventRepeat == 'Monthly')) {
        $tr_id = $this->createTaskRepeat($request, $addedtask, $taskDueDate);
        $addedtask->update(['task_tr_id' => $tr_id]);
        $this->neverEndTaskRepeats($request->calendEndDate);
      }
      $input = $request->categName;
      $alltasks = $this->categoryTask($input, $request->taskFilterDate, $request->catuserid);
      $task = array('taskStatus' => 'created', 'createTaskId' => $addedtask->id, 'db' => $alltasks);
    } else { // Update task..
      $taskFetch = Task::OfBusiness()->find($request->taskFormId);
      if ($taskFetch) {
        if ($request->has('isReminderSet')) {
          $reminderFetch = $taskFetch->reminders()->count();
          if ($reminderFetch) {
            $remindData = array('tr_is_set' => $request->isReminderSet, 'tr_hours' => $request->reminderHours, 'tr_datetime' => $reminder);
            TaskReminder::where('tr_task_id', $request->taskFormId)->update($remindData);
          } else {
            TaskReminder::insert(['tr_is_set' => $request->isReminderSet, 'tr_hours' => $request->reminderHours, 'tr_datetime' => $reminder, 'tr_task_id' => $request->taskFormId]);
          }
        } else
          $taskFetch->reminders()->delete();

        $taskFetch->update($insertData);
        $repeat = $taskFetch->repeat()->first();
        if (($request->eventRepeat != '') && ($request->eventRepeat == 'Daily' || $request->eventRepeat == 'Weekly' || $request->eventRepeat == 'Monthly')) {
          if ($taskFetch->task_tr_id && count($repeat)) {
            $tr_id = $this->createTaskRepeat($request, $taskFetch, $taskDueDate);

            unset($insertData['task_due_date']);

            $allTasks = Task::where('task_tr_id', $repeat->tr_id)->update(['task_tr_id' => $tr_id]);

            $siblingTasks = Task::where('task_tr_id', $tr_id)->where('id', '<>', $taskFetch->id)->whereDate('task_due_date', '>=', $taskFetch->task_due_date)->update($insertData);

            $repeat->delete();

            if ($taskFetch->task_due_date != $taskDueDate) {
              Task::where('task_tr_id', $repeat->tr_id)->where('id', '<>', $taskFetch->id)->whereDate('task_due_date', '>', $taskDueDate)->forcedelete();
              $this->neverEndTaskRepeats($request->calendEndDate);
            }
          } else {
            $tr_id = $this->createTaskRepeat($request, $taskFetch, $taskDueDate);
            $taskFetch->update(['task_tr_id' => $tr_id]);
            $this->neverEndTaskRepeats($request->calendEndDate);
          }
        } else {
          /* delete Existing task repeat */
          if (count($repeat)) {
            Task::where('task_tr_id', $repeat->tr_id)->where('id', '<>', $taskFetch->id)->whereDate('task_due_date', '>=', $taskFetch->task_due_date)->delete();

            $repeat->delete();
          }

          $taskFetch->update(['task_tr_id' => 0]);
        }
      }
      //previous code
      $input = $request->categName;
      $alltasks = $this->categoryTask($input, $request->taskFilterDate, $request->catuserid);
      $task = array('taskStatus' => 'updated', 'db' => $alltasks);
    }
    echo json_encode($task);
  }


  protected function delAssociatedServices($data)
  {
    $data['eventType'] = 'task';
    $this->delAssociatedEvents($data);
  }


  /*protected function unsetEventReccurence($event){
        $event->is_repeating = 0;
        if($event->task_parent_id)
            $event->task_parent_id = 0;
        $event->save();
  }*/

  protected function haltPrevRelatedEventsReccur($eventParentId)
  {
    $previousRelatedEvents = Task::where('task_parent_id', $eventParentId)->orWhere('id', $eventParentId)->orderBy('task_due_date', 'DESC')->get();
    if ($previousRelatedEvents->count()) {
      $latestEventDate = $previousRelatedEvents->first()->task_due_date;

      StaffEventRepeat::oftask()->where('ser_event_id', $eventParentId)->update(['ser_child_count' => $previousRelatedEvents->count() - 1]);

      $eventIds = $previousRelatedEvents->pluck('id')->toArray();

      $repeatTable = (new StaffEventRepeat)->getTable();
      DB::table($repeatTable)->where('ser_event_type', 'App\Task')->whereIn('ser_event_id', $eventIds)->update(['ser_repeat_end' => 'ON', 'ser_repeat_end_after_occur' => 0, 'ser_repeat_end_on_date' => $latestEventDate]);
    }
  }

  public function storecatId(Request $request)
  {
    $input = $request->categId;
    $input2 = $request->taskFilterDate;
    $this->neverEndTaskRepeats($request->taskMonthDate);
    $tasks = $this->categoryTask($input, $input2, $request->ownerId);
    //DD(Auth::user());
    //dd($tasks);
    echo json_encode($tasks);
  }

  public function storecheckbox(Request $request)
  {
    /*
    $isCompleted = $request->isCompleted;
    if($isCompleted)
        $insertData = array('is_completed' => $isCompleted ,'completed_by' => Auth::id() );
    else
        $insertData = array('is_completed' => $isCompleted ,'completed_by' => 0);

    $update = Task::OfBusiness()->where('id',$request->taskId)->update($insertData);
    
    if($update && $isCompleted)
      $result=array('ajaxStatus' =>'success' ,'username'=>Auth::user()->Fname);
    else if($update)
      $result=array('ajaxStatus' =>'success');
    else
      $result=array('ajaxStatus' =>'fail');
      echo json_encode($result);
   */
    $status = $request->status;
    if ($status == "incomplete") {
      $status = null;
      $completed_by = 0;
    } else {
      $completed_by = Auth::id();
    }
    $insertData = array('task_status' => $status, 'completed_by' => $completed_by);
    $update = Task::OfBusiness()->where('id', $request->taskid)->update($insertData);

    /*if( $status == "complete"){
        $result=array('ajaxStatus' =>'success' ,'username'=>Auth::user()->Fname ,'status'=>$status);
      }
      else if($status == "incomplete"){
        $result=array('ajaxStatus' =>'success' ,'username'=>Auth::user()->Fname ,'status'=>$status);
      }
      else if($status == "not required"){
        $result=array('ajaxStatus' =>'success' ,'username'=>Auth::user()->Fname ,'status'=>$status);
      }
      */
    if ($update) {
      $result = array('ajaxStatus' => 'success', 'username' => Auth::user()->Fname, 'status' => $status);
      echo json_encode($result);
    }
  }

  public function edittask(Request $request)
  {
    $taskrepeatId = Task::where('id', (int) $request->taskid)->pluck('task_tr_id')->first();
    $taskrepeat = TaskRepeat::where('tr_id', $taskrepeatId)->latest()->get()->take(1);

    echo json_encode($taskrepeat);
  }

  public function destroy(Request $request)
  {
    $msg = [];

    $Task = Task::OfBusiness()->find($request->id);
    $insertData = array('deleted_by' => Auth::id());
    if ($Task) {
      if ($request->has('targetEvents') && $request->targetEvents == 'future') {
        if ($Task->task_tr_id != 0) {
          $repeat = $Task->repeat()->first();
          $assosiatTask = Task::where('task_tr_id', $Task->task_tr_id)->whereDate('task_due_date', '>', $Task->task_due_date);
          $assosiatTask->update($insertData);
          $assosiatTask->delete();
          if (count($repeat))
            $repeat->delete();
        }
      }
      $Task->update($insertData);
      $Task->delete();
      $msg['status'] = 'deleted';
      $msg['id'] = $request->id;
      $msg['message'] = 'task successfully deleted';
    }

    return json_encode($msg);
  }

  public function destroycategory(Request $request)
  {
    $msg = [];

    $Category = TaskCategory::where('t_cat_business_id', Session::get('businessId'))->find($request->id);

    if ($Category) {
      //$Categorytasks = Task::OfBusiness()->where('task_category',$request->id)->delete();
      $insertData = array('deleted_by' => Auth::id());
      TaskCategory::where('t_cat_business_id', Session::get('businessId'))->where('id', $request->id)->update($insertData);
      $Category->delete();
      $msg['status'] = 'deleted';
      $msg['id'] = $request->id;
      $msg['message'] = 'category successfully deleted';
    }
    return json_encode($msg);
  }

  public function dashboardcalendar()
  {
    $categoriess = TaskCategory::where('t_cat_business_id', Session::get('businessId'))->orWhere('t_cat_business_id', 0)->get();
    if ($categoriess->count())
      foreach ($categoriess as $categories)
        $taskcategories[$categories->id] = $categories->t_cat_name;
    $newdata = [];
    $newdata = $this->getTc($categoriess);
    $tc = [];
    $tc = $newdata['tc'];
    //$eventRepeatIntervalOpt =[];
    $eventRepeatIntervalOpt = $newdata['eventRepeatIntervalOpt'];
    return view('dashboard.dashboardcalendar', compact('taskcategories', 'tc', 'eventRepeatIntervalOpt'));
  }

  public function getNotification()
  {
    $accountId = Auth::user()->account_id;
    if ($accountId == '0') {
      $staff = Staff::where('email', Auth::user()->email)->where('business_id',Auth::user()->business_id)->first();
    }else{
      $staff = Staff::where('id', $accountId)->first();
    }
    $historyData = [];
    $eventDataClass = $staff->eventClasses()->withTrashed()->pluck('sec_id')->toArray();
    $staffeventData = $staff->events()->withTrashed()->pluck('sess_id')->toArray();
    $staffHistoryClass = StaffEventHistory::whereIn('seh_event_id',$eventDataClass)->where('seh_event_type','App\StaffEventClass')->whereNotNull('seh_name')->where('created_at', '>=', Carbon::now()->subMinutes(1440))->orderBy('created_at','DESC')->get();
    $staffHistoryService = StaffEventHistory::whereIn('seh_event_id',$staffeventData)->where('seh_event_type','App\StaffEventSingleService')->whereNotNull('seh_name')->where('created_at', '>=', Carbon::now()->subMinutes(1440))->orderBy('created_at','DESC')->get();

    foreach($staffHistoryClass as $historyClass){
     $classData = StaffEventClass::where('sec_id',$historyClass->seh_event_id)->withTrashed()->first();
     $clasData = Clas::where('cl_id',$classData->sec_class_id)->first();
     $startTime = Carbon::parse($classData->sec_start_datetime);
     $endTime = Carbon::parse($classData->sec_end_datetime);
     $classDate = Carbon::parse($classData->sec_date);
      $historyData[] = [
                'class_name' => $clasData->cl_name,
                'performed_by' => $historyClass->seh_name,
                'text' => $historyClass->seh_text,
                'time' => $this->timeAgo($historyClass->created_at),
                'create' => $historyClass->created_at,
                'class_time' =>$startTime->format('h:i A') .'-'. $endTime->format('h:i A'),
                'class_date' =>  $classDate->format('D, d M Y')
              ];

    }

    foreach($staffHistoryService as $eventClass){
      $classData = StaffEventSingleService::where('sess_id',$eventClass->seh_event_id)->withTrashed()->first();
      $clasData = Service::where('id',$classData->sess_service_id)->first();
      $startTime = Carbon::parse($classData->sess_start_datetime);
      $endTime = Carbon::parse($classData->sess_end_datetime);
      $classDate = Carbon::parse($classData->sess_date);
       $historyData[] = [
                 'class_name' => $clasData->name,
                 'performed_by' => $eventClass->seh_name,
                 'text' => $eventClass->seh_text,
                 'time' => $this->timeAgo($eventClass->created_at),
                 'create' => $eventClass->created_at,
                 'class_time' =>$startTime->format('h:i A') .'-'. $endTime->format('h:i A'),
                 'class_date' =>  $classDate->format('D, d M Y')
               ];
 
     }
   
    // foreach ($staffeventData as $event) {
    //   $staffEventData = $event->histories()->where('created_at', '>=', '2020-02-01')->get();
    //   if (count($staffEventData)) {
    //     foreach ($staffEventData as $staffData) {
    //       $classStartTime = Carbon::parse($event->sess_start_datetime);
    //       $classEndTime = Carbon::parse($event->sess_end_datetime);
    //       $clasDate = Carbon::parse($event->sess_date);
    //       $historyData[] = [
    //         'class_name' => $event->service->name,
    //         'performed_by' => $staffData->seh_name,
    //         'text' => $staffData->seh_text,
    //         'time' => $this->timeAgo($staffData->created_at),
    //         'class_time' =>$classStartTime->format('h:i A') .'-'. $classEndTime->format('h:i A'),
    //         'create' => $staffData->created_at,
    //         'class_date' =>  $clasDate->format('D, d M Y')
    //       ];
    //       if(count($historyData) >= 5){
    //         break 2;
    //      }
    //     }
    //   }
    // }
    $keys = array_column($historyData, 'create');
    array_multisort($keys, SORT_DESC, $historyData);
    return json_encode($historyData);
  }
  public function timeAgo($timestamp)
  {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes = round($seconds / 60); // value 60 is seconds
    $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
    $days = round($seconds / 86400); //86400 = 24  60  60;
    $weeks = round($seconds / 604800); // 7*24*60*60;
    $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
    $years = round($seconds / 31553280); //(365+365+365+365+366)/5  24  60 * 60
    if ($seconds <= 60) {
      return "Just Now";
    } else if ($minutes <= 60) {
      if ($minutes == 1) {
        return "one minute ago";
      } else {
        return "$minutes minutes ago";
      }
    } else if ($hours <= 24) {
      if ($hours == 1) {
        return "an hour ago";
      } else {
        return "$hours hrs ago";
      }
    } else if ($days <= 7) {
      if ($days == 1) {
        return "yesterday";
      } else {
        return "$days days ago";
      }
    } else if ($weeks <= 4.3) {
      //4.3 == 52/12
      if ($weeks == 1) {
        return "a week ago";
      } else {
        return "$weeks weeks ago";
      }
    } else if ($months <= 12) {
      if ($months == 1) {
        return "a month ago";
      } else {
        return "$months months ago";
      }
    } else {
      if ($years == 1) {
        return "one year ago";
      } else {
        return "$years years ago";
      }
    }
  }

  /*public function getMonthlyTaskData(Request $request){
        if( ( isUserType(['Admin']) || isUserType(['Staff']) ) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-attendence')){
            if($request->ajax())
                return [];
            else
                abort(404);
        }

        if($request->has('categMonth')){
            $startDate = $request->categMonth;
            $carbon = new Carbon($startDate); 
        }
        else{
            $carbon = Carbon::now();
            $startDate = $carbon->startOfMonth()->toDateString();
        }
        
        if($request->view =="month"){
            $lastDate = $carbon->endOfMonth()->toDateString();
        }
        else if($request->view =="agendaWeek"){
            $lastDate = $carbon->endOfWeek()->toDateString();

        }
        else if($request->view =="agendaDay"){
            $lastDate = $startDate;
        }
        //print_r($lastDate);
        //DB::enableQueryLog();
        $categdata = Task::select('id','task_name','task_due_date','task_due_time','task_category','task_status')->whereIn('task_category',$request->categId)->where('task_due_date','>=',$startDate)->where('task_due_date','<=',$lastDate)->get();
        //dd(DB::getQueryLog());
        echo json_encode($categdata);
    }
    */
  public function traitfunction(Request $request)
  {
    //$this->getData($request);
    $isCommonCateg = TaskCategory::select('id')->whereIn('id', $request->categId)->where('t_cat_user_id', 0)
      ->where('t_cat_business_id', 0)->first();
    if ($isCommonCateg) {
      $catarray = array();
      $catarray = $request->categId;
      $CommonCategId = $isCommonCateg->id;

      $key = array_search($CommonCategId, $catarray);
      if (false !== $key) {
        unset($catarray[$key]);
      }
      //array_splice($catarray, 0, 1);
      //DB::enableQueryLog();
      $categdata = Task::where('task_due_date', '>=', $request->getEventsFrom)->where('task_due_date', '<=', $request->getEventsUpto)
        ->where(function ($query) use ($catarray, $CommonCategId) {
          $query->whereIn('task_category', $catarray)
            ->orWhere(function ($query) use ($CommonCategId) {
              $query->where('task_category', $CommonCategId)
                ->where('task_user_id', Auth::id());
            });
        })
        ->with('completer', 'reminders', 'categoryName')->select('id', 'task_name', 'task_due_date', 'task_due_time', 'task_category', 'task_status', 'is_repeating', 'completed_by', 'task_user_id')->get();
      //dd(DB::getQueryLog());
      //dd($categdata);
    } else {
      //DB::enableQueryLog();
      $categdata = Task::select('id', 'task_name', 'task_due_date', 'task_due_time', 'task_category', 'task_status', 'is_repeating', 'completed_by', 'task_user_id')->whereIn('task_category', $request->categId)->with('completer', 'reminders', 'categoryName')->where('task_due_date', '>=', $request->getEventsFrom)->where('task_due_date', '<=', $request->getEventsUpto)->where('task_user_id', Auth::id())->get();
      //dd(DB::getQueryLog());
    }

    echo json_encode($categdata);
  }

  function editChart(Request $request)
  {
    $msg = [];
    //dd($request->all());
    $inputData = $request->all();
    $chartSetting = ChartSetting::where('chart_business_id', Session::get('businessId'))->where('chart_type', $inputData['chart_type'])->first();

    $data = [];
    //$type='';
    foreach ($inputData as $key => $value) {
      /*if($key=='chart_type'){
            $type=$value;
          }*/
      if ($value == '1') {
        $str = $key . 'Color';
        $data[$key] = $inputData[$str];
      }
    }
    $settingData = json_encode($data);
    $chartSetting->chart_type = $inputData['chart_type'];
    $chartSetting->chart_setting_data = $settingData;
    //dd($chartSetting);
    if ($chartSetting->save()) {
      $msg['status'] = 'updated';
      $msg['setting'] = $chartSetting;
    } else {
      $msg['status'] = 'error';
    }
    echo json_encode($msg);
  }

  /* Start: Tasks modal for dashboard */
  public function getTasks(Request $request)
  {
    //dd($request->taskIds);

    $tasks = Task::with('completer', 'reminders', 'categoryName')->whereIn('id', $request->taskIds)->where('task_business_id', Session::get('businessId'))->get();
    /*$catId=$tasks->pluck('task_category');
      if(count($catId))
        $taskCatName = TaskCategory::whereIn('id',$catId)->pluck('t_cat_name')->toArray();

      $tasks->catName=$taskCatName;*/

    //echo json_encode($tasks);
    return json_encode($tasks);
  }
  /* End: Tasks modal for dashboard */

  public function callUpcomingTasksTimestamp()
  {
    //DB::enableQueryLog();
    //return $this->upcomingTasksTimestamp();
    //task_due_date
    $dueTasksTimestamp = [];
    $tasksReminderTimestamp = [];
    $tasks = $this->categoryTask(); //Getting all tasks of the logged in user
    //dd($tasks);
    if ($tasks->count()) {
      $tasks = $tasks->where('completed_by', 0); //Getting incomplete tasks only
      //$today = Carbon::today());
      //$tomorrow = Carbon::tomorrow();
      //Carbon::today();
      if ($tasks->count()) {
        $now = strtotime((new Carbon())->format('Y-m-d H:i')); //Getting current timestamp discarding seconds, i.e., 19.30.30 is 19.30.00
        foreach ($tasks as $task) {
          $taskDatetime = $task->task_due_date . ' ' . $task->task_due_time;
          $taskTimestamp = strtotime($taskDatetime);
          //$taskTimestamp = 1505711400;
          //echo "now = $now  timestamp = $taskTimestamp";
          if ($taskTimestamp * 1 >= $now) //Getting tasks whose due date are in future or in present
            //{
            $dueTasksTimestamp[$taskDatetime][] = $task->id;

          //echo $taskDatetime.'||'.$task->id.'<br>';
          //}

          if ($task->reminders->count()) {
            $reminder = $task->reminders->first();
            $remiderTimestamp = strtotime($reminder->tr_datetime);
            if ($remiderTimestamp >= $now) //Getting reminders who are in future or in present
              $tasksReminderTimestamp[$reminder->tr_datetime][] = $task->id;
          }
          //break;
        }
        ksort($dueTasksTimestamp);
        ksort($tasksReminderTimestamp);
      }
    }
    //return {"reminder":{"2017-09-16 13:30:00":[166]},"due":{"2017-09-15 16:30:00":[100],"2017-09-16 16:30:00":[166]}};
    //$tasksReminderTimestamp = ["2017-09-15 13:32:00"=>[34], "2017-09-14 13:32:00"=>[22], "2017-09-14 13:30:00"=>[21,24]];
    // $dueTasksTimestamp = ["2017-09-14 13:35:00"=>[21,22], "2017-09-14 14:00:00"=>[23]];
    //dd(['reminder'=>$tasksReminderTimestamp, 'due'=>$dueTasksTimestamp]);
    //$tasksReminderTimestamp = ["2017-09-16 13:30:00"=> [166]] ;
    //var_dump($tasksReminderTimestamp);

    //dd(DB::getQueryLog());
    //return DB::getQueryLog();

    return json_encode(['reminder' => $tasksReminderTimestamp, 'due' => $dueTasksTimestamp]);
  }

  protected function categoryTask($catId = 0, $duedate = '', $ownerId = 0)
  {

    if (!$duedate)
      $duedate = Carbon::now()->toDateString();

    if (!$catId) {
      /*$commonCategory = TaskCategory::where('t_cat_user_id',0)->where('t_cat_business_id',0)->select('id')->first();
          $commonCategoryId = $commonCategory->id;*/

      $commonCategoryId = TaskCategory::where('t_cat_user_id', 0)->where('t_cat_business_id', 0)->pluck('id')->toArray();
      //$commonCategoryId = $commonCategory->id;
      DB::enableQueryLog();
      $query = Task::with('completer', 'reminders')->OfTasks($duedate);


      if (!isSuperUser()) {
        $query->where(function ($q) use ($commonCategoryId) {
          $q->whereNotIn('task_category', $commonCategoryId)
            ->orWhere(function ($qr) use ($commonCategoryId) {
              $qr->whereIn('task_category', $commonCategoryId)->where('task_user_id', Auth::id());
            });
        });
      }

      return $query->get();
      //dd(DB::getQueryLog());
    } else {
      $Category = TaskCategory::where('id', $catId)->first();

      if (($Category->t_cat_user_id == 0) && ($Category->t_cat_business_id == 0) && $Category->t_cat_name != 'Birthday') {
        $authId = ($ownerId) ? $ownerId : Auth::id();
        return Task::with('completer', 'reminders')->where('task_category', $catId)->where('task_user_id', $authId)->OfTasks($duedate)->get();
      } else {
        return Task::with('completer', 'reminders')->where('task_category', $catId)->OfTasks($duedate)->get();
      }
    }
  }

  public function activities(Request $request){
   
    $accountId = Auth::user()->account_id;
    if ($accountId == '0') {
      $staff = Staff::where('email', Auth::user()->email)->where('business_id',Auth::user()->business_id)->first();
    }else{
      $staff = Staff::where('id', $accountId)->first();
    }
    if($request->datefilter){
      $arr = explode('-', $request->datefilter);
      $start_date = Carbon::parse($arr[0]);
      $end_date = Carbon::parse($arr[1]);
      $eventDataClass = $staff->eventClasses()->withTrashed()->pluck('sec_id')->toArray();
      $staffeventData = $staff->events()->withTrashed()->pluck('sess_id')->toArray();
      $staffHistoryClass = StaffEventHistory::whereIn('seh_event_id',$eventDataClass)->where('seh_event_type','App\StaffEventClass')->whereNotNull('seh_name')->whereBetween('created_at',[$start_date,$end_date])->orderBy('created_at','DESC')->pluck('seh_id')->toArray();
      $staffHistoryService = StaffEventHistory::whereIn('seh_event_id',$staffeventData)->where('seh_event_type','App\StaffEventSingleService')->whereNotNull('seh_name')->whereBetween('created_at',[$start_date,$end_date])->orderBy('created_at','DESC')->pluck('seh_id')->toArray();
      $merged = array_merge($staffHistoryClass,$staffHistoryService);
      $historyData = StaffEventHistory::whereIn('seh_id',$merged)->orderBy('created_at','DESC')->paginate(200);
    }else{

    $eventDataClass = $staff->eventClasses()->withTrashed()->pluck('sec_id')->toArray();
    $staffeventData = $staff->events()->withTrashed()->pluck('sess_id')->toArray();
    $staffHistoryClass = StaffEventHistory::whereIn('seh_event_id',$eventDataClass)->where('seh_event_type','App\StaffEventClass')->whereNotNull('seh_name')->orderBy('created_at','DESC')->pluck('seh_id')->toArray();
    $staffHistoryService = StaffEventHistory::whereIn('seh_event_id',$staffeventData)->where('seh_event_type','App\StaffEventSingleService')->whereNotNull('seh_name')->orderBy('created_at','DESC')->pluck('seh_id')->toArray();
    $merged = array_merge($staffHistoryClass,$staffHistoryService);
    $historyData = StaffEventHistory::whereIn('seh_id',$merged)->orderBy('created_at','DESC')->paginate(200);
    }
  
    return view('activity_notification', compact('historyData'));
  }

 
}
