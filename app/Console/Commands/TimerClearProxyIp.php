<?php

namespace App\Console\Commands;

use App\Http\Business\ProxyIpBusiness;
use Illuminate\Console\Command;

class TimerClearProxyIp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:timer-clear-proxy-ip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时清理代理IP';

    /**
     * 代理IP 业务
     *
     * @var
     */
    protected $proxy_ip_business;

    /**
     * 构造函数
     *
     * GrabProxyIp constructor.
     * @param ProxyIpBusiness $proxy_ip_business
     */
    public function __construct(ProxyIpBusiness $proxy_ip_business)
    {
        parent::__construct();
        $this->proxy_ip_business = $proxy_ip_business;
    }

    /**
     * @author jiangxianli
     * @created_at 2017-12-25 08:56:47
     */
    public function handle()
    {
        $this->proxy_ip_business->timerClearProxyIp();
    }
}
