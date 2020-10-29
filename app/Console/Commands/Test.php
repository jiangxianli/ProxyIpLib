<?php

namespace App\Console\Commands;

use App\Http\Business\ProxyIpBusiness;
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
        Helper::logFlag($this->signature);

        $urls = [
            "https://proxy.coderbusy.com/zh-hans/ops/daily/topics/4945422720.html"
        ];
        $this->proxy_ip_business->grabProcess($urls, ".panel-body", function ($tr) {
            $rows = [];
            $pattern = "/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}:\d{1,4}@(HTTPS|HTTP)#/";
            var_dump($tr->htmls());
            if (preg_match_all($pattern, $tr->htmls(), $matches)) {
//                dd($matches[0]);
                foreach ($matches[0] as $item) {
                    $ip = substr($item, 0, strrpos($item, ":"));
                    $port = substr($item, strrpos($item, ":") + 1, strrpos($item, "@") - strrpos($item, ":") - 1);
                    $protocol = substr($item, strrpos($item, "@") + 1, strrpos($item, "#") - strrpos($item, "@") - 1);
                    $rows[] = [$ip, $port, 2, strtolower($protocol)];
                }
            }
            dd($rows);
            return $rows;
        }, false);
    }
}
