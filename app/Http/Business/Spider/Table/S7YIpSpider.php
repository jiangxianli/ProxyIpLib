<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class S7YIpSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "https://www.7yip.cn/free/?action=china&page=1",
        "https://www.7yip.cn/free/?action=china&page=2",
        "https://www.7yip.cn/free/?action=china&page=3",
        "https://www.7yip.cn/free/?action=china&page=4",
        "https://www.7yip.cn/free/?action=china&page=5",
        "https://www.7yip.cn/free/?action=china&page=6",
        "https://www.7yip.cn/free/?action=china&page=7",
        "https://www.7yip.cn/free/?action=china&page=8",
        "https://www.7yip.cn/free/?action=china&page=9",
        "https://www.7yip.cn/free/?action=china&page=10",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "#content .container table tr";

    /**
     * @param $tr
     * @return array|void
     * @author jiangxianli
     * @created_at 2021-03-01 13:46
     */
    public function getItems($tr)
    {
        $ip = $tr->find('td:eq(0)')->text();
        $port = $tr->find('td:eq(1)')->text();
        $anonymity = $tr->find('td:eq(2)')->text() == "高匿" ? 2 : 1;
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