<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        'App\Console\Commands\SyncPosCommand',
        'App\Console\Commands\SyncUpdatePosCommand'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $unix_timestamp = now()->timestamp;
        $schedule->command('syncpos:init')
            ->dailyAt('00:01')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path("logs/syncpos-$unix_timestamp.log"));

        $schedule->command('syncpos:update')
            ->dailyAt('00:39')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path("logs/syncposupdate-$unix_timestamp.log"));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
