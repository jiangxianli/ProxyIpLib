<?php

namespace App\Jobs;

use App\Http\Business\ProxyIpBusiness;
use Carbon\Carbon;

class ClearProxyIpJob extends Job
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = "clear-ip";

    /**
     * 透明度
     *
     * @var
     */
    private $proxy_ip;

    /**
     * @var
     */
    private $expired_at ;

    /**
     * ProxyIpLocationJob constructor.
     * @param array $proxy_ip
     */
    public function __construct(array $proxy_ip)
    {
        $this->proxy_ip = $proxy_ip;
        $this->expired_at = time() + 120;
    }

    /**
     * @param ProxyIpBusiness $proxy_ip_business
     * @throws \App\Exceptions\JsonException
     * @author jiangxianli
     * @created_at 2019-10-23 16:47
     */
    public function handle(ProxyIpBusiness $proxy_ip_business)
    {
        //超时
        if ($this->expired_at <= time()) {
            return;
        }

        //检查是否存在
        $ip = $proxy_ip_business->getProxyIpList([
            'unique_id' => $this->proxy_ip['unique_id'],
            'first'     => 'true'
        ]);
        if (!$ip) {
            return;
        }

        $redis = app('redis');
        $ip_cache_map = "proxy_ips:clear-ip-cache-map";
        $cache_key = sprintf("%s://%s:%s", $this->proxy_ip['protocol'], $this->proxy_ip['ip'], $this->proxy_ip['port']);
        //获取已失败次数
        $ip_cache_times = $redis->hget($ip_cache_map, $cache_key);
        if (!empty($ip_cache_times) && $ip_cache_times >= 3) {
            $proxy_ip_business->deleteProxyIp($this->proxy_ip['unique_id']);
            return;
        }

        try {
            //测速及可用性检查
            $speed = $proxy_ip_business->ipSpeedCheck($this->proxy_ip['ip'], $this->proxy_ip['port'], $this->proxy_ip['protocol']);
            //更新测速信息
            $proxy_ip_business->updateProxyIp($this->proxy_ip['unique_id'], [
                'speed'        => $speed,
                'validated_at' => Carbon::now(),
            ]);
            $redis->hset($ip_cache_map, $cache_key, 0);
        } catch (\Exception $exception) {
            $redis->hset($ip_cache_map, $cache_key, empty($ip_cache_times) ? 1 : $ip_cache_times + 1);
        }

        usleep(0.2 * 1000 * 1000);
    }
}
