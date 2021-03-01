<?php

namespace App\Jobs;

use App\Http\Business\Spider\BaseSpider;

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
    private $spider;

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
     * @param BaseSpider $spider
     */
    public function __construct(BaseSpider $spider)
    {
        $this->spider = $spider;
        $this->expired_at = time() + 5 * 60;
    }

    /**
     * @author jiangxianli
     * @created_at 2021-03-01 16:38
     */
    public function handle()
    {
        try {
            //实例化Redis
            $redis = app('redis');

            $grab_key = "proxy_ips:grab_flag:" . strtolower(get_class($this->spider));
            //正在抓取中
            if ($redis->exists($grab_key)) {
                return;
            }

            $redis->set($grab_key, time());
            $redis->expireAt($grab_key, time() + 300);

            $this->spider->handle();

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
