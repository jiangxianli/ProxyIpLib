<?php

namespace App\Console\Commands;

use App\Http\Business\ProxyIpBusiness;
use Illuminate\Console\Command;

class GrabProxyIp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:grab-proxy-ip {origin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取代理IP';

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
        switch ($this->argument('origin')) {
            case 'kuidaili':
                $this->proxy_ip_business->grabKuaiDaiLi();
                break;
            case 'xicidaili':
                $this->proxy_ip_business->grabXiCiDaiLi();
                break;
        }
    }
}
