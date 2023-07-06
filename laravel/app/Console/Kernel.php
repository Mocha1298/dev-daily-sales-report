<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('getapigoersdev')->everyTwoMinutes();
        // $schedule->command('getapigoersdev')->everyMinute();
        // ->sendOutputTo('/log/file')
        // ->onSuccess(function () {
        //     Log::info('The command was successful');
        // })
        // ->onFailure(function () {
        //     Log::error('The command failed');
        // });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
