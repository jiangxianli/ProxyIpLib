<?php

namespace App\Http\Business\Spider\Html;

use App\Http\Business\Spider\HtmlSpider;
use QL\Ext\AbsoluteUrl;
use QL\QueryList;

class CodeBusyIpSpider extends HtmlSpider
{
    public $is_open = false;
    
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
            "https://proxy.coderbusy.com/zh-hans/ops/daily.html"
        ];
    }

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = ".panel-body";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 14:27
     */
    public function getItems($tr)
    {
        $rows = [];
        $pattern = "/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}:\d{1,4}@(HTTPS|HTTP)#/";
        if (preg_match_all($pattern, $tr->htmls(), $matches)) {
            foreach ($matches[0] as $item) {
                $ip = substr($item, 0, strrpos($item, ":"));
                $port = substr($item, strrpos($item, ":") + 1, strrpos($item, "@") - strrpos($item, ":") - 1);
                $protocol = substr($item, strrpos($item, "@") + 1, strrpos($item, "#") - strrpos($item, "@") - 1);
                $rows[] = [$ip, $port, 2, strtolower($protocol)];
            }
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
        $ql = QueryList::getInstance();
        $ql->use(AbsoluteUrl::class);
        $ql->use(AbsoluteUrl::class, 'absoluteUrl', 'absoluteUrlHelper');

        $urls = [];
        foreach ($this->getUrls() as $page_url) {
            $links = $ql->get($page_url)->absoluteUrl('https://proxy.coderbusy.com')->find('.panel-heading a')->attrs('href');
            $urls = array_merge($urls, $links);
        }
        return $urls;
    }

    /**
     * @author jiangxianli
     * @created_at 2021-03-01 17:16
     */
    public function handle()
    {
        $this->grabProcess($this->getSecondPageUrls(), $this->td_selector, [$this, "getItems"], $this->is_use_proxy);
    }
}