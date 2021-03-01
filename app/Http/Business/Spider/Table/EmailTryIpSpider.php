<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class EmailTryIpSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "http://emailtry.com/index/1",
        "http://emailtry.com/index/2",
        "http://emailtry.com/index/3",
        "http://emailtry.com/index/4",
        "http://emailtry.com/index/5",
        "http://emailtry.com/index/6",
        "http://emailtry.com/index/7",
        "http://emailtry.com/index/8",
        "http://emailtry.com/index/9",
        "http://emailtry.com/index/10",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "table#proxy-table1>tr";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 13:51
     */
    public function getItems($tr)
    {
        list($ip, $port) = explode(":", $tr->find('td:eq(0)')->text());
        $protocol = "http";
        $anonymity = 2;

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