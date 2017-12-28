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
        $schedule->command('command:timer-clear-proxy-ip')->everyTenMinutes();
        $schedule->command('command:proxy-ip-location')->everyFiveMinutes()->appendOutputTo('/tmp/proxy.log');
        $schedule->command('command:grab-proxy-ip kuidaili')->everyTenMinutes()->appendOutputTo('/tmp/proxy.log');
        $schedule->command('command:grab-proxy-ip xicidaili')->everyTenMinutes()->appendOutputTo('/tmp/proxy.log');
        $schedule->command('command:grab-proxy-ip goubanjia')->everyTenMinutes()->appendOutputTo('/tmp/proxy.log');
        $schedule->command('command:grab-proxy-ip sixsixip')->everyTenMinutes()->appendOutputTo('/tmp/proxy.log');
        $schedule->command('command:grab-proxy-ip yundaili')->everyTenMinutes()->appendOutputTo('/tmp/proxy.log');
        $schedule->command('command:grab-proxy-ip data5u')->everyTenMinutes()->appendOutputTo('/tmp/proxy.log');;
    }
}
