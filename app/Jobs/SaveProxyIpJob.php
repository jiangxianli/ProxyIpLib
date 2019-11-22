<?php

namespace App\Jobs;

use App\Http\Business\ProxyIpBusiness;

class SaveProxyIpJob extends Job
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = "add-ip";

    /**
     * IP
     *
     * @var
     */
    private $ip;

    /**
     * 端口
     *
     * @var
     */
    private $port;

    /**
     * 协议
     *
     * @var
     */
    private $protocol;

    /**
     * 透明度
     *
     * @var
     */
    private $anonymity;

    /**
     * 来源站点
     *
     * @var
     */
    private $host;

    /**
     * SaveProxyIpJob constructor.
     * @param $host
     * @param $ip
     * @param $port
     * @param $protocol
     * @param $anonymity
     */
    public function __construct($host, $ip, $port, $protocol, $anonymity)
    {
        $this->host = $host;
        $this->ip = $ip;
        $this->port = $port;
        $this->protocol = $protocol;
        $this->anonymity = $anonymity;
    }

    /**
     * @param ProxyIpBusiness $proxy_ip_business
     * @author jiangxianli
     * @created_at 2019-10-23 15:31
     */
    public function handle(ProxyIpBusiness $proxy_ip_business)
    {
        $redis = app('redis');

        $ip_cache_map = "proxy_ips:add-ip-cache-map";

        $cache_key = sprintf("%s://%s:%s", $this->protocol, $this->ip, $this->port);
        //获取已抓取次数
        $ip_cache_times = $redis->hget($ip_cache_map, $cache_key);
        if (!empty($ip_cache_times) && $ip_cache_times >= 10) {
            return;
        }

        try {
            //速度检测
            $proxy_ip_business->ipSpeedCheck($this->ip, $this->port, $this->protocol);
            //添加 代理IP
            $proxy_ip_business->addProxyIp($this->host, $this->ip, $this->port, $this->protocol, $this->anonymity);

        } catch (\Exception $exception) {
            app("Logger")->error("代理IP入库失败", [
                'host'       => $this->host,
                'ip'         => $this->ip,
                'port'       => $this->port,
                'protocol'   => $this->protocol,
                'anonymity'  => $this->anonymity,
                'error_code' => $exception->getCode(),
                'error_msg'  => method_exists($exception, 'formatError') ? $exception->formatError() : $exception->getMessage(),
            ]);
        }

        $redis->hset($ip_cache_map, $cache_key, empty($ip_cache_times) ? 1 : $ip_cache_times + 1);

        sleep(2);
    }
}
