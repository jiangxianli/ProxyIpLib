<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class XiCiDaiLiSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "https://www.xicidaili.com/nn/",
        "https://www.xicidaili.com/nn/2",
        "https://www.xicidaili.com/nn/3",
        "https://www.xicidaili.com/nt/",
        "https://www.xicidaili.com/nt/2",
        "https://www.xicidaili.com/nt/3",
        "https://www.xicidaili.com/wn/",
        "https://www.xicidaili.com/wn/2",
        "https://www.xicidaili.com/wn/3",
        "https://www.xicidaili.com/wt/",
        "https://www.xicidaili.com/wt/2",
        "https://www.xicidaili.com/wt/3",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "table tbody tr";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 13:51
     */
    public function getItems($tr)
    {
        $ip = trim($tr->find('td:eq(1)')->text());
        $port = trim($tr->find('td:eq(2)')->text());
        $anonymity = str_contains($tr->find('td:eq(4)')->text(), "高匿") ? 2 : 1;
        $protocol = str_contains($tr->find('td:eq(5)')->text(), "HTTPS") ? "https" : "http";

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