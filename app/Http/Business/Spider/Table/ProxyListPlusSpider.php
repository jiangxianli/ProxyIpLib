<?php

namespace App\Http\Business\Spider\Table;

use App\Http\Business\Spider\TableSpider;

class ProxyListPlusSpider extends TableSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "https://list.proxylistplus.com/update-1",
        "https://list.proxylistplus.com/Fresh-HTTP-Proxy-List-1",
        "https://list.proxylistplus.com/Fresh-HTTP-Proxy-List-2",
        "https://list.proxylistplus.com/Fresh-HTTP-Proxy-List-3",
        "https://list.proxylistplus.com/Fresh-HTTP-Proxy-List-4",
        "https://list.proxylistplus.com/Fresh-HTTP-Proxy-List-5",
        "https://list.proxylistplus.com/Fresh-HTTP-Proxy-List-6",
    ];

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = "table.bg tr";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 13:51
     */
    public function getItems($tr)
    {
        $ip = $tr->find('td:eq(1)')->text();
        $port = $tr->find('td:eq(2)')->text();
        $protocol = "http";
        $anonymity = $tr->find('td:eq(3)')->text() == "transparent" ? 1 : 2;

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