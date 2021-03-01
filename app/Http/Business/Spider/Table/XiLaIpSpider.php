<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class XiLaIpSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "http://www.xiladaili.com/gaoni/",
        "http://www.xiladaili.com/gaoni/2/",
        "http://www.xiladaili.com/gaoni/3/",
        "http://www.xiladaili.com/gaoni/4/",
        "http://www.xiladaili.com/gaoni/5/",
        "http://www.xiladaili.com/gaoni/6/",
        "http://www.xiladaili.com/putong/",
        "http://www.xiladaili.com/putong/2/",
        "http://www.xiladaili.com/putong/3/",
        "http://www.xiladaili.com/putong/4/",
        "http://www.xiladaili.com/putong/5/",
        "http://www.xiladaili.com/putong/6/",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "table.fl-table tbody tr";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 13:51
     */
    public function getItems($tr)
    {
        list($ip, $port) = explode(":", $tr->find('td:eq(0)')->text());
        $protocol = str_contains($tr->find('td:eq(1)')->text(), "HTTPS") ? "https" : "http";
        $anonymity = str_contains($tr->find('td:eq(1)')->text(), "透明") ? 1 : 2;

        return [$ip, $port, $anonymity, $protocol];
    }

    /**
     * @author jiangxianli
     * @created_at 2021-03-01 17:14
     */
    public function handle()
    {
        $this->grabProcess($this->urls, $this->td_selector, [$this, "getItems"], $this->is_use_proxy);
    }
}