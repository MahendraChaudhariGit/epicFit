<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\GoalBuddy;
use App\GoalBuddyMilestones;
use App\GoalBuddyHabit;
use App\GoalBuddyHabitMetaData;
use App\GoalBuddyTask;
use App\Http\Traits\HelperTrait;
use Session;
use Auth;
use DB;

class GbCalendarController extends Controller{
	use  HelperTrait;

    public function index(){
		
		return view('goal-buddy.calendar');
	}
}