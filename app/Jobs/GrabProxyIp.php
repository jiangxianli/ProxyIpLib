<?php

namespace App\Jobs;

use App\Http\Business\ProxyIpBusiness;

class GrabProxyIp extends Job
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = "grab-ip";

    /**
     * 透明度
     *
     * @var
     */
    private $origin;

    /**
     * @var
     */
    private $expired_at;

    /**
     * 设置最大超时时间
     *
     * @var int
     */
    public $timeout = 180;

    /**
     * GrabProxyIp constructor.
     * @param $origin
     */
    public function __construct($origin)
    {
        $this->origin = $origin;
        $this->expired_at = time() + 5 * 60;
    }

    /**
     * @param ProxyIpBusiness $proxy_ip_business
     * @author jiangxianli
     * @created_at 2019-11-27 14:08
     */
    public function handle(ProxyIpBusiness $proxy_ip_business)
    {
        try {

            //实例化Redis
            $redis = app('redis');

            $grab_key = "proxy_ips:grab_flag:" . $this->origin;
            //正在抓取中
            if ($redis->exists($grab_key)) {
                return;
            }

            $redis->set($grab_key, time());
            $redis->expireAt($grab_key, time() + 300);

            switch ($this->origin) {
                case 'kuidaili':
                    $proxy_ip_business->grabKuaiDaiLi();
                    return;
                case 'ip3366':
                    $proxy_ip_business->grabIp3366();
                    return;
                case '89ip':
                    $proxy_ip_business->grab89Ip();
                    return;
                case 'xila':
                    $proxy_ip_business->xiLaIp();
                    return;
                case 'emailtry':
                    $proxy_ip_business->emailtryIp();
                    return;
                case 'qinghua':
                    $proxy_ip_business->qinghuaIp();
                    return;
                case 'xsdaili':
                    $proxy_ip_business->xsdailiIp();
                    return;
                case 'kxdaili':
                    $proxy_ip_business->kxdailiIp();
                    return;
                case 'nima':
                    $proxy_ip_business->nimaIp();
                    return;
                case 'super':
                    $proxy_ip_business->superIp();
                    return;
                case 'xici':
                    $proxy_ip_business->xiciIp();
                    return;
            }

            $this->delete();

        } catch (\Exception $exception) {
            app("Logger")->error("抓取网页错误", [
                'err_code'  => $exception->getCode(),
                'err_msg'   => $exception->getMessage(),
                'err_trace' => $exception->getTraceAsString()
            ]);
        }
    }
}
