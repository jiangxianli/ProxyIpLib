<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class ProxyScanSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "https://www.proxyscan.io/home/filterresult?limit=100&page=1&status=1&ping=&selectedType=HTTP&selectedType=HTTPS&sortPing=false&sortTime=true&sortUptime=false&_=%s",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "tr";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 13:51
     */
    public function getItems($tr)
    {
        $ip = $tr->find('th:eq(0)')->text();
        $port = $tr->find('td:eq(0)')->text();
        $protocol = str_contains($tr->find('td:eq(0)')->text(), "HTTPS") ? "https" : "http";
        $anonymity = str_contains($tr->find('td:eq(4)')->text(), "Transparent") ? 1 : 2;

        return [$ip, $port, $anonymity, $protocol];
    }

    /**
     * @author jiangxianli
     * @created_at 2021-03-01 17:14
     */
    public function handle()
    {
        foreach ($this->urls as $key => $url) {
            $this->urls[$key] = sprintf($url, time());
        }

        $this->grabProcess($this->urls, $this->td_selector, [$this, "getItems"], $this->is_use_proxy);
    }
}