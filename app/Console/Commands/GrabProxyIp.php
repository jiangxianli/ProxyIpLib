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
                return;
            case 'ip3366':
                $this->proxy_ip_business->grabIp3366();
                return;
            case '89ip':
                $this->proxy_ip_business->grab89Ip();
                return;
            case 'xila':
                $this->proxy_ip_business->xiLaIp();
                return;
            case 'emailtry':
                $this->proxy_ip_business->emailtryIp();
                return;
            case 'qinghua':
                $this->proxy_ip_business->qinghuaIp();
                return;
            case 'xsdaili':
                $this->proxy_ip_business->xsdailiIp();
                return;
            case 'kxdaili':
                $this->proxy_ip_business->kxdailiIp();
                return;
            case 'nima':
                $this->proxy_ip_business->nimaIp();
                return;
        }
    }
}
