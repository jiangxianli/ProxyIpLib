<?php

namespace App\Console\Commands;

use App\Http\Business\ProxyIpBusiness;
use App\Http\Business\Spider\CronSpider;
use App\Http\Business\Spider\Html\CheckerProxyIpSpider;
use App\Http\Business\Spider\Html\CodeBusyIpSpider;
use App\Http\Business\Spider\Html\FoxToolsIpSpider;
use App\Http\Business\Spider\Html\ProxyListIpSpider;
use App\Http\Business\Spider\Html\XsDaiLiIpSpider;
use App\Http\Business\Spider\Table\EmailTryIpSpider;
use App\Http\Business\Spider\Table\FreeProxyCzSpider;
use App\Http\Business\Spider\Table\Ip3366Spider;
use App\Http\Business\Spider\Table\KuaiDaiLiSpider;
use App\Http\Business\Spider\Table\KxDaiLiSpider;
use App\Http\Business\Spider\Table\NiMaDaiLiSpider;
use App\Http\Business\Spider\Table\ProxyListMeIpSpider;
use App\Http\Business\Spider\Table\ProxyListPlusSpider;
use App\Http\Business\Spider\Table\QingHuaDaiLiSpider;
use App\Http\Business\Spider\Table\S7YIpSpider;
use App\Http\Business\Spider\Table\S89IpSpider;
use App\Http\Business\Spider\Html\SuperFastIpSpider;
use App\Http\Business\Spider\Table\XiCiDaiLiSpider;
use App\Http\Business\Spider\Table\XiLaIpSpider;
use App\Http\Common\Helper;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试';

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
     * @created_at 2019-10-23 16:47
     */
    public function handle()
    {
        app(CronSpider::class)->cronTimer();
//        app(EmailTryIpSpider::class)->handle();

        return ;

        dd(new \ReflectionClass(__NAMESPACE__.'\ProxyIpLocation'));
        Helper::logFlag($this->signature);

        $this->proxy_ip_business->checkerproxyIp();
    }
}
