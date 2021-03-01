<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class Ip3366Spider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "http://www.ip3366.net/free/?stype=1&page=1",
        "http://www.ip3366.net/free/?stype=1&page=2",
        "http://www.ip3366.net/free/?stype=1&page=3",
        "http://www.ip3366.net/free/?stype=1&page=4",
        "http://www.ip3366.net/free/?stype=2&page=1",
        "http://www.ip3366.net/free/?stype=2&page=2",
        "http://www.ip3366.net/free/?stype=2&page=3",
        "http://www.ip3366.net/free/?stype=2&page=4",
        "http://www.ip3366.net/free/?stype=3&page=1",
        "http://www.ip3366.net/free/?stype=3&page=2",
        "http://www.ip3366.net/free/?stype=3&page=3",
        "http://www.ip3366.net/free/?stype=3&page=4",
        "http://www.ip3366.net/free/?stype=4&page=1",
        "http://www.ip3366.net/free/?stype=4&page=2",
        "http://www.ip3366.net/free/?stype=4&page=3",
        "http://www.ip3366.net/free/?stype=4&page=4",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "#list table tr";

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
        $anonymity = str_contains($tr->find('td:eq(2)')->text(), ["高匿"]) ? 2 : 1;
        $protocol = strtolower($tr->find('td:eq(3)')->text());

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