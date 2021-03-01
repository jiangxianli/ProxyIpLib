<?php

namespace App\Http\Business\Spider\Html;

use App\Http\Business\Spider\HtmlSpider;

class ProxyListIpSpider extends HtmlSpider
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
            "https://www.proxy-list.download/api/v1/get?type=http",
            "https://www.proxy-list.download/api/v1/get?type=https",
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
        return [
            "https://www.proxy-list.download/api/v1/get?type=http",
            "https://www.proxy-list.download/api/v1/get?type=https",
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