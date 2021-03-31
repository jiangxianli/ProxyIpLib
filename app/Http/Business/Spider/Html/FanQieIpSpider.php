<?php

namespace App\Http\Business\Spider\Html;

use App\Http\Business\Spider\HtmlSpider;
use Carbon\Carbon;

class FanQieIpSpider extends HtmlSpider
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
        $rows = [];
        $html = strip_tags($html);
        $lines = explode("\n", $html);
        foreach ($lines as $line) {
            if (!str_contains($line, ":")) {
                continue;
            }
            $line = str_replace("\r", "", $line);
            $line = str_replace("\n", "", $line);
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
        $key = config('site.fan_qie_ip_key');
        if (empty($key)) {
            return [];
        }

        $urls = [];

        for ($i = 1; $i <= 20; $i++) {
            $urls[] = "http://x.fanqieip.com/index.php?s=/Api/IpManager/adminFetchFreeIpRegionInfoList&uid=13967&ukey=$key&limit=10&format=0&page=$i";
        }

        return $urls;
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