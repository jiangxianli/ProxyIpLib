<?php

namespace App\Http\Business\Spider\Html;

use App\Http\Business\Spider\HtmlSpider;
use Carbon\Carbon;

class CheckerProxyIpSpider extends HtmlSpider
{
    /**
     * 一级页面地址
     *
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 14:21
     */
    public function getUrls()
    {
        return [
            "https://checkerproxy.net/api/archive/" . Carbon::today()->subDays(1)->format("Y-m-d")
        ];
    }

    /**
     * @param $html
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 14:27
     */
    public function getItems($html)
    {
        $data = (array)json_decode($html, true);
        $ips = array_column($data, 'addr');
        unset($html, $data);
        $rows = [];
        foreach ($ips as $line) {
            list($ip, $port) = explode(":", $line);
            $anonymity = 1;
            $protocol = "http";
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
            "https://checkerproxy.net/api/archive/" . Carbon::today()->subDays(1)->format("Y-m-d")
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