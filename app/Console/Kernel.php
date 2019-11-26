<?php

namespace App\Console;

use App\Console\Commands\ClearCacheKeyEveryDay;
use App\Console\Commands\GrabProxyIp;
use App\Console\Commands\HotIpByHours;
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
        //
        HotIpByHours::class,
        //
        ClearCacheKeyEveryDay::class,
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
        $schedule->command('command:timer-clear-proxy-ip')->everyMinute()->withoutOverlapping();
        $schedule->command('command:proxy-ip-location')->everyMinute()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip kuidaili')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip ip3366')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip 89ip')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip xila')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip emailtry')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip qinghua')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip xsdaili')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip kxdaili')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip nima')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:grab-proxy-ip super')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('command:hot-ip-by-hours')->hourly()->withoutOverlapping();
        $schedule->command('command:clear-cache-key-every-day')->dailyAt("20:00")->withoutOverlapping();
    }
}
