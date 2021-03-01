<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class NiMaDaiLiSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "http://www.nimadaili.com/putong/",
        "http://www.nimadaili.com/putong/2/",
        "http://www.nimadaili.com/putong/3/",
        "http://www.nimadaili.com/putong/4/",
        "http://www.nimadaili.com/putong/5/",
        "http://www.nimadaili.com/gaoni/1/",
        "http://www.nimadaili.com/gaoni/2/",
        "http://www.nimadaili.com/gaoni/3/",
        "http://www.nimadaili.com/gaoni/4/",
        "http://www.nimadaili.com/gaoni/5/",
        "http://www.nimadaili.com/http/1/",
        "http://www.nimadaili.com/http/2/",
        "http://www.nimadaili.com/http/3/",
        "http://www.nimadaili.com/http/4/",
        "http://www.nimadaili.com/http/5/",
        "http://www.nimadaili.com/https/1/",
        "http://www.nimadaili.com/https/2/",
        "http://www.nimadaili.com/https/3/",
        "http://www.nimadaili.com/https/4/",
        "http://www.nimadaili.com/https/5/",
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
        $anonymity = str_contains($tr->find('td:eq(1)')->text(), "普通") ? 1 : 2;

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