<?php

namespace App\Console\Commands;

use App\Http\Business\CronSpider;
use Illuminate\Console\Command;

class GrabProxyIp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:grab-proxy-ip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取代理IP';

    /**
     * 构造函数
     *
     * GrabProxyIp constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param CronSpider $cron_spider
     * @throws \ReflectionException
     * @author jiangxianli
     * @created_at 2021-03-01 16:54
     */
    public function handle(CronSpider $cron_spider)
    {
        $cron_spider->cronTimer();
    }
}
