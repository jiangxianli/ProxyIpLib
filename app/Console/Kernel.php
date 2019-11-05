<?php

namespace App\Console;

use App\Console\Commands\GrabProxyIp;
use App\Console\Commands\ProxyIpLocation;
use App\Console\Commands\TimerClearProxyIp;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        GrabProxyIp::class,
        //
        TimerClearProxyIp::class,
        //
        ProxyIpLocation::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
        $schedule->command('command:timer-clear-proxy-ip')->everyMinute()->withoutOverlapping()->runInBackground();
        $schedule->command('command:proxy-ip-location')->everyMinute()->withoutOverlapping()->runInBackground();
        $schedule->command('command:grab-proxy-ip kuidaili')->everyFiveMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command('command:grab-proxy-ip ip3366')->everyFiveMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command('command:grab-proxy-ip 89ip')->everyFiveMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command('command:grab-proxy-ip xila')->everyFiveMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command('command:grab-proxy-ip emailtry')->everyFifteenMinutes()->withoutOverlapping()->runInBackground();
        $schedule->command('command:grab-proxy-ip qinghua')->everyFiveMinutes()->withoutOverlapping()->runInBackground();
    }
}
