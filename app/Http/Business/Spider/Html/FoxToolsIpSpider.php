<?php

namespace App\Http\Business\Spider\Html;

use App\Http\Business\Spider\HtmlSpider;

class FoxToolsIpSpider extends HtmlSpider
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
            "http://api.foxtools.ru/v2/Proxy.txt?page=1",
        ];
    }

    /**
     * @param $html
     * @return array|void
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
            $protocol = "https";
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
            "http://api.foxtools.ru/v2/Proxy.txt?page=1",
        ];
    }

    /**
     * @author jiangxianli
     * @created_at 2021-03-01 17:17
     */
    public function handle()
    {
        $this->grabHtmlProcess($this->getSecondPageUrls(), [$this, "getItems"], $this->is_use_proxy);
    }
}