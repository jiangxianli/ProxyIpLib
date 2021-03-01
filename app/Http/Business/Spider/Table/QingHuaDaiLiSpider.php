<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class QingHuaDaiLiSpider extends TableSpider
{
    public $is_open = false;

    /**
     * @var array
     */
    protected $urls = [
        "http://www.qinghuadaili.com/free/1/",
        "http://www.qinghuadaili.com/free/2/",
        "http://www.qinghuadaili.com/free/3/",
        "http://www.qinghuadaili.com/free/4/",
        "http://www.qinghuadaili.com/free/5/",
        "http://www.qinghuadaili.com/free/6/",
        "http://www.qinghuadaili.com/free/7/",
        "http://www.qinghuadaili.com/free/8/",
        "http://www.qinghuadaili.com/free/9/",
        "http://www.qinghuadaili.com/free/10/",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = ".container-fluid table tbody tr";

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