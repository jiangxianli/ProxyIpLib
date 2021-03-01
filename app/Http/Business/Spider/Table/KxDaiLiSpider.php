<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class KxDaiLiSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "http://www.kxdaili.com/dailiip.html",
        "http://www.kxdaili.com/dailiip/1/2.html",
        "http://www.kxdaili.com/dailiip/1/3.html",
        "http://www.kxdaili.com/dailiip/1/4.html",
        "http://www.kxdaili.com/dailiip/1/5.html",
        "http://www.kxdaili.com/dailiip/1/6.html",
        "http://www.kxdaili.com/dailiip/1/7.html",
        "http://www.kxdaili.com/dailiip/2/1.html",
        "http://www.kxdaili.com/dailiip/2/2.html",
        "http://www.kxdaili.com/dailiip/2/3.html",
        "http://www.kxdaili.com/dailiip/2/4.html",
        "http://www.kxdaili.com/dailiip/2/5.html",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = ".hot-product-content table tbody tr";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 13:51
     */
    public function getItems($tr)
    {
        $ip = trim($tr->find('td:eq(0)')->text());
        $port = trim($tr->find('td:eq(1)')->text());
        $anonymity = str_contains($tr->find('td:eq(2)')->text(), "高匿") ? 2 : 1;
        $protocol = str_contains($tr->find('td:eq(3)')->text(), "HTTPS") ? "https" : "http";

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