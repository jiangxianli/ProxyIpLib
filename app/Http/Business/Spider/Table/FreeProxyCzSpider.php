<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class FreeProxyCzSpider extends TableSpider
{
    public $is_open = false;
    
    /**
     * @var array
     */
    protected $urls = [
        "http://free-proxy.cz/zh/proxylist/country/all/http/ping/level1/1",
        "http://free-proxy.cz/zh/proxylist/country/all/http/ping/level1/2",
        "http://free-proxy.cz/zh/proxylist/country/all/http/ping/level1/3",
        "http://free-proxy.cz/zh/proxylist/country/all/http/ping/level1/4",
        "http://free-proxy.cz/zh/proxylist/country/all/http/ping/level1/5",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "table#proxy_list tr";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 13:51
     */
    public function getItems($tr)
    {
        $ip = $tr->find('td:eq(0)')->text();
        $port = $tr->find('td:eq(1)')->text();
        $protocol = strtolower($tr->find('td:eq(2)')->text());
        $anonymity = $tr->find('td:eq(6)')->text() == "高匿" ? 2 : 1;

        return [$ip, $port, $anonymity, $protocol];
    }

    /**
     * @author jiangxianli
     * @created_at 2021-03-01 17:15
     */
    public function handle()
    {
        $this->grabProcess($this->urls, $this->td_selector, [$this, "getItems"], $this->is_use_proxy);
    }
}