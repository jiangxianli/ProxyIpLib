<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class S89IpSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "http://www.89ip.cn/index_1.html",
        "http://www.89ip.cn/index_2.html",
        "http://www.89ip.cn/index_3.html",
        "http://www.89ip.cn/index_4.html",
        "http://www.89ip.cn/index_5.html",
        "http://www.89ip.cn/index_6.html",
        "http://www.89ip.cn/index_7.html",
        "http://www.89ip.cn/index_8.html",
        "http://www.89ip.cn/index_9.html",
        "http://www.89ip.cn/index_10.html",
        "http://www.89ip.cn/index_11.html",
        "http://www.89ip.cn/index_12.html",
        "http://www.89ip.cn/index_13.html",
        "http://www.89ip.cn/index_14.html",
        "http://www.89ip.cn/index_15.html",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "table.layui-table tbody tr";

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
        $anonymity = 2;
        $protocol = "http";

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