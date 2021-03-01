<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class KuaiDaiLiSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "http://www.kuaidaili.com/free/inha/",
        "http://www.kuaidaili.com/free/inha/2/",
        "http://www.kuaidaili.com/free/inha/3/",
        "http://www.kuaidaili.com/free/inha/4/",
        "http://www.kuaidaili.com/free/inha/5/",
        "http://www.kuaidaili.com/free/intr/",
        "http://www.kuaidaili.com/free/intr/2/",
        "http://www.kuaidaili.com/free/intr/3/",
        "http://www.kuaidaili.com/free/intr/4/",
        "http://www.kuaidaili.com/free/intr/5/",
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
     * @created_at 2021-03-01 13:46
     */
    public function getItems($tr)
    {
        $ip = $tr->find('td:eq(0)')->text();
        $port = $tr->find('td:eq(1)')->text();
        $anonymity = $tr->find('td:eq(2)')->text() == "高匿名" ? 2 : 1;
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