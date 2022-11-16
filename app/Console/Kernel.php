<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        // 'App\Console\Commands\ClientMembershipUpdate',
        // Commands\UpdateClientMembership::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        // $schedule->command('memb:update')
        //     ->daily();
        // $schedule->command('memb:update')->everyMinute();
        // $schedule->call('App\Http\Controllers\UpdateClientsMembershipController@cronMembUpdate')->everyMinute();
        // everyTenMinutes();
        $schedule->call('App\Http\Controllers\UpdateClientsMembershipController@cronMembUpdate')->twiceDaily(4, 16);

        $schedule->call('App\Http\Controllers\Helper@allStaffEventsUpdateByCron')->twiceDaily(4, 16);
        $schedule->call('App\Http\Controllers\Setings\Calendar\CalendarSettingController@sendAppointmentSummary')->dailyAt('12:00')->timezone('Pacific/Auckland');
        $schedule->call('App\Http\Controllers\GoalBuddy\GoalBuddyController@due_message')->dailyAt('1:00');
        $schedule->call('App\Http\Controllers\UpdateClientsMembershipController@cronMembUpdate')->hourly();
         $schedule->call('App\Http\Controllers\GoalNotification@emailNotification')->hourly();
        // $schedule->call('App\Http\Controllers\GoalNotification@emailNotification')->everyMinute();
        
    }
}
