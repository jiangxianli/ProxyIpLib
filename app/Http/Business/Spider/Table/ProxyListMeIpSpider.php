<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class ProxyListMeIpSpider extends TableSpider
{
    public $is_open = false;

    /**
     * @var array
     */
    protected $urls = [
        "https://proxylist.me/?page=1",
        "https://proxylist.me/?page=2",
        "https://proxylist.me/?page=3",
        "https://proxylist.me/?page=4",
        "https://proxylist.me/?page=5",
        "https://proxylist.me/?page=6",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "#datatable-row-highlight tr";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 13:51
     */
    public function getItems($tr)
    {
        $ip = trim($tr->find('td:eq(0) a')->text());
        $port = trim($tr->find('td:eq(1)')->text());
        $anonymity = 1;
        $protocol = str_contains($tr->find('td:eq(3)')->text(), "https") ? "https" : "http";

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