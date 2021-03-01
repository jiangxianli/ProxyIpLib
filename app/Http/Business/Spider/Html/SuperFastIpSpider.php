<?php

namespace App\Http\Business\Spider\Html;

use App\Http\Business\Spider\HtmlSpider;

class SuperFastIpSpider extends HtmlSpider
{
    /**
     * @var array
     */
    protected $urls = [
        "https://api.superfastip.com/ip/freeip?page=1",
        "https://api.superfastip.com/ip/freeip?page=2",
    ];

    /**
     * @param $html
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 14:27
     */
    public function getItems($html)
    {
        $data = (array)json_decode($html, true);
        $items = isset($data['freeips']) ? $data['freeips'] : [];
        $rows = [];
        foreach ($items as $item) {
            $ip = $item['ip'];
            $port = $item['port'];
            $anonymity = str_contains($item['level'], "高级") ? 2 : 1;;
            $protocol = strtolower($item['type']);
            $rows[] = [$ip, $port, $anonymity, $protocol];
        }

        return $rows;
    }

    /**
     * 二级页面地址
     *
     * @author jiangxianli
     * @created_at 2021-03-01 14:16
     */
    public function getSecondPageUrls()
    {
        return [
            "https://api.superfastip.com/ip/freeip?page=1",
            "https://api.superfastip.com/ip/freeip?page=2",
        ];
    }

    /**
     * @author jiangxianli
     * @created_at 2021-03-01 17:16
     */
    public function handle()
    {
        $this->grabHtmlProcess($this->getSecondPageUrls(), [$this, "getItems"], $this->is_use_proxy);
    }
}