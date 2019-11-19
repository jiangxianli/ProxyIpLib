<?php

namespace App\Console\Commands;

use App\Http\Business\ProxyIpBusiness;
use App\Http\Common\Helper;
use Illuminate\Console\Command;

class ClearCacheKeyEveryDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:clear-cache-key-every-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日清理缓存Key';

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
        Helper::logFlag($this->signature);

        $redis = app('redis');
        $redis->del("proxy_ips:clear-ip-cache-map");
        $redis->del("proxy_ips:add-ip-cache-map");
    }
}
