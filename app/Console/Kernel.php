<?php

namespace App\Console;

use App\Console\Commands\ClearCacheKeyEveryDay;
use App\Console\Commands\GrabProxyIp;
use App\Console\Commands\HotIpByHours;
use App\Console\Commands\ProxyIpLocation;
use App\Console\Commands\Test;
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
        //
        HotIpByHours::class,
        //
        ClearCacheKeyEveryDay::class,
        //
        Test::class,
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
        $schedule->command('command:timer-clear-proxy-ip')->everyFiveMinutes()->runInBackground();
        $schedule->command('command:proxy-ip-location')->everyMinute()->runInBackground();
        $schedule->command('command:grab-proxy-ip checkerproxy')->dailyAt("02:00");
        $schedule->command('command:clear-cache-key-every-day')->dailyAt("20:00");
        $schedule->command('command:hot-ip-by-hours')->hourly()->runInBackground();

        $schedule->command('command:grab-proxy-ip kuidaili')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip ip3366')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip 89ip')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip xila')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip emailtry')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip qinghua')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip xsdaili')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip kxdaili')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip nima')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip super')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip xici')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip foxtools')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip proxyList')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip proxylistme')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip 7yip')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip free-proxy')->everyFifteenMinutes();
        $schedule->command('command:grab-proxy-ip plus')->everyFifteenMinutes();
    }
}
