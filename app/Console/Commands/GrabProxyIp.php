<?php

namespace App\Console\Commands;

use App\Http\Business\ProxyIpBusiness;
use App\Http\Common\Helper;
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
        Helper::logFlag(str_replace(" {origin}", "-" . $this->argument('origin'), $this->signature));

        switch ($this->argument('origin')) {
            case 'kuidaili':
                $this->proxy_ip_business->grabKuaiDaiLi();
                break;
            case 'xicidaili':
                $this->proxy_ip_business->grabXiCiDaiLi();
                break;
            case 'goubanjia':
                $this->proxy_ip_business->grabGouBanJia();
                return;
            case 'sixsixip':
                $this->proxy_ip_business->grabSixSixIp();
                return;
            case 'yundaili':
                $this->proxy_ip_business->grabYunDaiLi();
                return;
            case 'data5u':
                $this->proxy_ip_business->grabData5U();
                return;
            case 'ip3366':
                $this->proxy_ip_business->grabIp3366();
                return;
            case 'pcdaili':
                $this->proxy_ip_business->grabPCDaiLi();
                return;
        }
    }
}
